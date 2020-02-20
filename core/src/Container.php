<?php

namespace App;

use App\Model\User;
use App\Model\UserToken;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Dotenv\Dotenv;
use Throwable;

if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(dirname(__dir__)));
}

/**
 * @property Request $request
 * @property Response $response
 * @property-read Manager capsule
 * @property-read DatabaseManager $db
 */
class Container extends \Slim\Container
{
    /** @var User $user */
    public $user = null;


    /**
     * Container constructor.
     *
     * @param array $container
     */
    public function __construct($container = [])
    {
        parent::__construct($container);

        try {
            $dotenv = new Dotenv(true);
            $dotenv->loadEnv(dirname(__DIR__) . '/.env');
        } catch (Throwable $e) {
            exit($e->getMessage());
        }

        $this['capsule'] = function () {
            $capsule = new Manager;
            $capsule->addConnection([
                'driver' => getenv('DB_DRIVER'),
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT'),
                'prefix' => getenv('DB_PREFIX'),
                'database' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => getenv('DB_CHARSET'),
                'collation' => getenv('DB_COLLATION'),
            ]);
            $capsule->setEventDispatcher(new Dispatcher());
            $capsule->setAsGlobal();

            return $capsule;
        };
        $this->capsule->bootEloquent();

        $this['db'] = function () {
            return $this->capsule->getDatabaseManager();
        };
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function makeToken($id)
    {
        $time = time();

        // Invalidate old tokens
        UserToken::query()
            ->where(['user_id' => $id, 'active' => true])
            ->where('valid_till', '<', date('Y-m-d H:i:s', $time))
            ->update(['active' => false]);

        /** @var UserToken $user_token */
        if ($user_token = UserToken::query()->where(['user_id' => $id, 'created_at' => date('Y-m-d H:i:s', $time), 'active' => true])->first()) {
            $token = $user_token->token;
        } else {
            $data = [
                'id' => $id,
                'iat' => $time,
                'exp' => $time + getenv('JWT_EXPIRE'),
            ];
            $token = JWT::encode($data, getenv('JWT_SECRET'));

            $user_token = new UserToken([
                'user_id' => $id,
                'token' => $token,
                'valid_till' => date('Y-m-d H:i:s', $data['exp']),
                'ip' => $this->request->getAttribute('ip_address'),
            ]);
            $user_token->save();
        }

        // Limit active tokens
        if (UserToken::query()->where(['user_id' => $id, 'active' => true])->count() > getenv('JWT_MAX')) {
            UserToken::query()
                ->where(['user_id' => $id, 'active' => true])
                ->orderBy('updated_at', 'asc')
                ->orderBy('created_at', 'asc')
                ->first()
                ->update(['active' => false]);
        }

        return $token;
    }


    /**
     * Check token and load user
     */
    public function loadUser()
    {
        if ($token = $this->getToken($this->request)) {
            /** @var UserToken $user_token */
            if ($user_token = UserToken::query()->where(['user_id' => $token->id, 'token' => $token->token, 'active' => true])->first()) {
                if ($user_token->valid_till > date('Y-m-d H:i:s')) {
                    /** @var User $user */
                    if ($user = $user_token->user()->where(['active' => true])->first()) {
                        $this->user = $user;

                        return true;
                    }
                } else {
                    $user_token->active = false;
                    $user_token->save();
                }
            }
        }

        return false;
    }


    /**
     * @param Request $request
     *
     * @return object|null
     */
    protected function getToken($request)
    {
        $pcre = '#Bearer\s+(.*)$#i';
        $token = null;

        $header = $request->getHeaderLine('Authorization');
        if (!empty($header) && preg_match($pcre, $header, $matches)) {
            $token = $matches[1];
        } else {
            $cookies = $request->getCookieParams();
            if (isset($cookies['auth._token.local'])) {
                $token = preg_match($pcre, $cookies['auth._token.local'], $matches)
                    ? $matches[1]
                    : $cookies['auth._token.local'];
            };
        }

        if ($token) {
            try {
                $decoded = JWT::decode($token, getenv('JWT_SECRET'), ['HS256', 'HS512', 'HS384']);
                $decoded->token = $token;
                $this->request = $this->request->withAttribute('token', $token);

                return $decoded;
            } catch (Throwable $e) {
                return null;
            }
        }

        return null;
    }
}
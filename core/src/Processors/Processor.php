<?php

namespace App\Processors;

use App\Container;
use Error;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Slim\Http\Response;

abstract class Processor
{
    /** @var Container */
    protected $container;
    protected $properties = [];
    protected $scope = '';
    protected $total_time = 0;
    protected $query_time = 0;
    protected $queries = 0;
    protected $debug = [];


    /**
     * Processor constructor.
     *
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->total_time = microtime(true);

        if (getenv('PROCESSORS_STAT') || getenv('PROCESSORS_DEBUG')) {
            $container->db->listen(function ($query) use (&$count) {
                /** @var QueryExecuted $query */
                if (getenv('PROCESSORS_STAT')) {
                    $this->query_time += $query->time;
                    $this->queries++;
                }
                if (getenv('PROCESSORS_DEBUG')) {
                    foreach ($query->bindings as $v) {
                        $query->sql = preg_replace('#\?#', is_numeric($v) ? $v : "'{$v}'", $query->sql, 1);
                    }
                    $this->debug[] = $query->sql;
                }
            });
        }
    }


    /**
     * @return Response
     */
    public function process()
    {
        $this->setProperties(
            $this->container->request->isGet()
                ? $this->container->request->getQueryParams()
                : $this->container->request->getParams()
        );
        $check = $this->checkScope();
        if ($check !== true) {
            return !$this->container->user
                ? $this->failure('Authorization required', 401)
                : $this->failure($check);
        }
        $method = strtolower($this->container->request->getMethod());
        if (!method_exists($this, $method)) {
            return $this->failure('Could not find requested method', 404);
        }
        try {
            return $this->{$method}();
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        } catch (Error $e) {
            return $this->failure($e->getMessage());
        }
    }


    /**
     * @return string|bool
     */
    protected function checkScope()
    {
        if ($this->container->request->isOptions() || empty($this->scope) || PHP_SAPI == 'cli') {
            return true;
        }

        if ($this->container->user) {
            if ($this->container->user->id === 1) {
                return true;
            }

            // User have permission for all methods
            if (in_array($this->scope, $this->container->user->role->scope)) {
                return true;
            }

            // User has permission only for requested method
            $scope = $this->scope . '/' . strtolower($this->container->request->getMethod());
            if (in_array($scope, $this->container->user->role->scope)) {
                return true;
            }
        }

        return 'You have no "' . $this->scope . '/' . strtolower($this->container->request->getMethod()) . '" permission';
    }


    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    protected function getProperty($key, $default = null)
    {
        return isset($this->properties[$key])
            ? $this->properties[$key]
            : $default;
    }


    /**
     * @param $key
     * @param $value
     */
    protected function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }


    /**
     * @param $key
     */
    protected function unsetProperty($key)
    {
        unset($this->properties[$key]);
    }


    /**
     * @return array
     */
    protected function getProperties()
    {
        return $this->properties;
    }


    /**
     * @param array $properties
     */
    protected function setProperties(array $properties)
    {
        $this->properties = $properties;
    }


    /**
     * @return Response
     */
    public function options()
    {
        return $this->success()
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT, PATCH, UPDATE');
    }


    /**
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    public function success($data = [], $code = 200)
    {
        if (getenv('PROCESSORS_DEBUG') && $this->container->user && $this->container->user->hasScope('debug')) {
            $data['debug'] = $this->debug;
        }
        if (getenv('PROCESSORS_STAT')) {
            $data['stat'] = [
                'memory' => memory_get_peak_usage(true),
                'queries' => $this->queries,
                'query_time' => round(($this->query_time / 1000), 7),
                'total_time' => round((microtime(true) - $this->total_time), 7),
            ];
        }

        $response = $this->container->response
            ->withJson($data, $code, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        if (getenv('CORS')) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $this->container->request->getHeader('HTTP_ORIGIN'));
        }

        return $response;
    }


    /**
     * @param string $message
     * @param int $code
     *
     * @return Response
     */
    public function failure($message = '', $code = 422)
    {
        $response = $this->container->response
            ->withJson($message, $code, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        if (getenv('CORS')) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $this->container->request->getHeader('HTTP_ORIGIN'));
        }

        return $response;
    }
}
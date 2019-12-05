export default ctx => {
  ctx.$axios.defaults.headers.common = {'X-Requested-With': 'XMLHttpRequest'};

  ctx.$axios.interceptors.response.use(function (config) {
    return config;
  }, function (error) {
    if (error.response) {
      switch (error.response.status) {
        case 422:
          ctx.app.$notify.error({
            message: error.response.data
          });
          break;
        case 401:
          ctx.app.$notify.error({
            message: error.response.data
          });
          break;
        default:
          ctx.app.$notify.error({
            message: error.response.data
          });
      }
    } else {
      ctx.app.$notify.error({
        message: 'Unknown error'
      });
    }

    return Promise.reject(error.response);
  });
}
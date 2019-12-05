import Vue from 'vue'

export default ({app}, inject) => {

  inject('password', (length) => {
    if (!length) {
      length = 12;
    }
    let charset = 'abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let password = '';

    for (let i = 0, n = charset.length; i < length; ++i) {
      password += charset.charAt(Math.floor(Math.random() * n));
    }

    return password;
  });

  Vue.filter('number', (value) => {
    return value
      ? value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
      : 0;
  });

  Vue.filter('ucfirst', (value) => {
    if (value.length) {
      value = value.charAt(0).toUpperCase() + value.toLowerCase().slice(1);
    }
    return value;
  });

}

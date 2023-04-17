var feCartUpdatedEventEmitter = {
  callbacks: [],
  subscribe: function(callback) {
    this.callbacks.push(callback);
  },
  emit: function() {
    $.ajax('index.php?route=fe/api/checkout/cart/getProductCount')
      .done(res => {
        this.callbacks.forEach((callback) => {
          callback(res.productCount);
        });
      });

  }
};

$(document).ready(() => {
  feCartUpdatedEventEmitter.emit();
});

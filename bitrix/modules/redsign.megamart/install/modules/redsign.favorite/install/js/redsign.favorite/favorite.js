(function(BX, document, window){
	'use strict';

  if (!window.RS) {
    window.RS = {};
  }

  if (!!window.RS.Favorite || !BX) {
    return;
  }

  window.RS.Favorite = function(arParams)
	{
    //this.ajaxUrl = '/bitrix/components/redsign/lightbasket.basket/ajax.php';
    this.products = [];
  }

  window.RS.Favorite.prototype = {

    init: function(ids) {

      if (ids) {
        ids = $.isArray(ids) ? ids : [ids];

        if (this.products.length > 0) {
          this.products = BX.util.array_merge(this.products, ids);
        } else {
          this.products = ids;
        }

      }

      return this.products;
    },
    
    request: function(iProductId) {
      var params = {};

      params.action = 'add2wishlist';
      params.element_id = iProductId;
      params.sessid = BX.bitrix_sessid();

      BX.ajax({
        url: location.href,
        method: 'POST',
        data: params,
        dataType: 'json',
        onsuccess: function(result) {
          //result = BX.parseJSON(result);

          if (result.STATUS == 'OK') {

            if (BX.util.in_array(iProductId, this.products)) {
              this.remove(iProductId);
            } else {
              this.add(iProductId);
            }
            
            BX.onCustomEvent('change.rs_favorite');

          } else {
            console.warn('favorite - error responsed');
            e.preventDefault();
          }
        }.bind(this),
      });

    },
    
    add: function(iProductId) {

      if (iProductId == undefined) {
         return false;
      }

      this.products.push(iProductId);
    },
    
    remove: function(iProductId) {

      if (iProductId == undefined) {
         return false;
      }

      if (this.products.indexOf(iProductId) > -1) {
        this.products.splice(this.products.indexOf(iProductId), 1);
      }
    },
    
    getItems: function() {
      return this.products;
    },
  };


  window.RS.Favorite = new RS.Favorite;

}(BX, document, window));

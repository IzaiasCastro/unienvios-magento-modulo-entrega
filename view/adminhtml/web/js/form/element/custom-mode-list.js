define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function ($, _, uiRegistry, select) {
    'use strict';
    return select.extend({
        initialize: function (){
 this._super();
            var customMode = this._super().initialValue;
 setTimeout(function () {
                var customCMSPages = uiRegistry.get('index = custom_cms_pages');
                    if (customMode == 1) {
 customCMSPages.show();
                    } else{
                        customCMSPages.hide();
 }
 
            }, 1000);
            return this;
        },
 
        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            this.fieldDepend(value);
            return this._super();
        },
 
        /**
         * Update field dependency
         *
         * @param {String} value
         */
        fieldDepend: function (value) {
            setTimeout(function () {
                var customCMSPages = uiRegistry.get('index = custom_cms_pages');
                if (value == 1) {
 customCMSPages.show();
                } else {
                    customCMSPages.hide();
                }
            }, 500);
            return this;
        }
    });
});

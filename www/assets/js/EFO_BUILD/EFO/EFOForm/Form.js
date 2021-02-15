(function () {
    window.Eve = window.Eve || {};
    window.Eve.EFO = window.Eve.EFO || {};
    window.Eve.EFO.Form ? false : initPlugin();

    /**
     * абстракт для forms
     * форма  - абстрактная, для создания конкретной формы - нужно оверрайдить
     * метод генерации списка полей
     * @returns {undefined}
     */
    function initPlugin() {
        var EFO = window.Eve.EFO, U = EFO.U;
        function F() {
            U.AbstractError();
        }
        F.xInheritE(EFO.Handlable);
        F.prototype._container = null;
        F.prototype.pages = null;
        /**
         *override 
         * @returns {F}
         */
        F.prototype.onInit = function () {
            var Z =  EFO.Handlable.prototype.onInit.apply(this,Array.prototype.slice.call(arguments));
            this.initForm();
            /*
             *Иерархия - форма -> страница ->[Блок->сабблок] -> поле
             *любое поле наследуется от виджета
             */
            this.initFormEvents();
            return Z;
        };      

        F.prototype.getContainer = function () {
            return this._container;
        };
        
        F.prototype.setContainer = function($x){
            this._container = $x;
            this._container?this.handle.appendTo(this._container):false;
            return this.show();
        };

        F.prototype.show = function () {
            return this._container?EFO.Handlable.prototype.show.apply(this,Array.prototype.slice.call(arguments)):this;            
        };
                
        F.prototype.getContentTemplate = function () {
            this.threadError("form::getContentTemplate must be overriden");
            return 'formContent';
        };

        /**
         * override
         * @returns {undefined}
         */
        F.prototype.getCssClass = function () {
            return 'EFOForm';
        };


        /**
         * сабтемплаты, нужные для рендеринга текущего
         * @returns {Array}
         */
        F.prototype.enumSubTemplates = function () {
            return [];
        };
        /**
         * миксины Fieldable и Monitorable
         * не используются - их методы встроены в сам блок формы (а равно и в поля)
         */
        
        F.prototype.initFormEvents = function(){
            this.handle.on('change','[data-monitor]',this.onMonitor())
        }
       
        EFO.Form = F;
    }
})();
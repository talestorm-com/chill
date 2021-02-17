(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.js('/assets/vendor/tinymce/js/tinymce/tinymce.min.js')
    ];
    //</editor-fold>
    function initPlugin() {
        //<editor-fold defaultstate="collapsed" desc="Инициализация">
        var EFO = window.Eve.EFO, U = EFO.U, PAR = EFO.flatController, PARP = PAR.prototype, APS = Array.prototype.slice;
        var TPLS = null;
        /*<?=$this->build_templates('TPLS')?>*/
        EFO.TemplateManager().addObject(TPLS, MC); // префикс класса
        var STYLE = null;
        /*<?=$this->create_style("{$this->MC}",'STYLE')?>*/
        EFO.SStyleDriver().registerStyleOInstall(STYLE);
        function F() {
            return  (F.is(this) ? this.init() : F.F());
        }
        F.xInheritE(PAR);
        F.mixines = ['Roleable', 'Loaderable', 'Commandable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '
        F.prototype.onBeforeInit = function () {
            this.instance_id = U.UID();
            return PARP.onBeforeInit.apply(this, APS.call(arguments));
        };
        F.prototype.onInit = function (eops) {
            PARP.onInit.apply(this, APS.call(arguments));
            this.editor_id = ['a', MD, 'editor', this.instance_id].join('');
            this.eops = U.isObject(eops) ? eops : null;
            this.getRole('editor').attr('id',this.editor_id);
            return this;
        };

        F.prototype.getContentTemplate = function () {
            return EFO.TemplateManager().get([MC, 'Main'].join('.'));
        };
        F.prototype.getControllerAlias = function () {
            return MC;
        };
        F.prototype.getCssClass = function () {
            return MC;
        };

        //</editor-fold>   

        F.prototype.destroy_editor = function () {
            if (this.editor) {
                this.editor.destroy();
                this.editor = null;
            }
            return this;
        };


        F.prototype.get_default_options = function () {
            return {
                plugins: [
                    'autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor textcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat '
            };
        };
        F.prototype.init_editor = function () {
            this.destroy_editor();
            var options = this.eops ? this.eops : this.get_default_options();
            options.selector = ["#", this.editor_id].join('');
            this.editor_promise = tinymce.init(options).then(this.on_editor_ready_x.bindToObject(this), this.on_editor_error.bindToObject(this));
            return this;
        };

        F.prototype.on_editor_error = function () {
        };

        F.prototype.on_editor_ready_x = function () {
            this.editor = tinymce.get(this.editor_id);
            this.editor.load();
            return this;
        };


        F.prototype.setText = function (x) {
            x = U.NEString(x, '');
            this.getRole('editor').val(x);
            this.editor ? this.editor.load() : 0;
            return this;
        };

        F.prototype.getText = function () {
            this.editor ? this.editor.save() : 0;
            return U.NEString(this.getRole('editor').val(), '');
        };




        //<editor-fold defaultstate="collapsed" desc="клинер">
        F.prototype.clear = function () {
            if (this.editor) {
                this.editor.destroy();
            } else {
                if (this.editor_promise) {
                    this.editor_promise.then(this.clear.bindToObject(this));
                }
            }

        };

        //</editor-fold>        

        //<editor-fold defaultstate="collapsed" desc="misc &&callback">
        F.prototype.onRequiredComponentFail = function () {
            U.TError("component load error");
        };
        Y.reportSuccess(FQCN, F);// конструктор, не инстанс
        //</editor-fold>
    }
    //<editor-fold defaultstate="collapsed" desc="dependecy resolver">
    if (imports.length) {
        window.Eve.EFO.EFOPromise.waitForArray(imports)
                .done(initPlugin)
                .fail(function () {
                    Y.report_fail(FQCN, "Ошибке при загрузке зависимости");
                });
    } else {
        initPlugin();
    }
    //</editor-fold>
})();
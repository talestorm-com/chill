(function () {
    var H = null, MC = '<?=$this->MC?>', MD = '<?=$this->MD?>', FQCN = '<?=$this->fqcn?>';
    //<editor-fold defaultstate="collapsed" desc="Импорт">
    var Y = window.Eve.EFO.Com();
    var imports = [
        Y.js('/assets/vendor/tinymce/js/tinymce/tinymce.min.js'),
        Y.js('/assets/vendor/codemirror/lib/codemirror.js'),
        Y.js('/assets/vendor/codemirror/addon/mode/multiplex.js'),
        Y.js('/assets/vendor/codemirror/addon/runmode/colorize.js'),
        Y.js('/assets/vendor/codemirror/mode/xml/xml.js'),
        Y.js('/assets/vendor/codemirror/mode/css/css.js'),
        Y.js('/assets/vendor/codemirror/mode/javascript/javascript.js'),
        Y.js('/assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js'),
        Y.css('/assets/vendor/codemirror/lib/codemirror.css')
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
        F.mixines = ['Roleable', 'Loaderable', 'Commandable', 'Monitorable'];
        U.initMixines(F);
        F.prototype.MD = MD;
        //</editor-fold>        
        //<editor-fold defaultstate="collapsed" desc="Обвес">   '       
        F.prototype.onInit = function (eops) {
            PARP.onInit.apply(this, APS.call(arguments));
            this.editor_id = ['a', MD, 'editor', this.controller_id].join('');
            this.eops = U.isObject(eops) ? eops : null;
            //this.getRole('editor').attr('id', this.editor_id);
            this.code_mirror = CodeMirror.fromTextArea(this.getRole('editor-cm').get(0), {
                mode: "text/html", lineNumbers: true, height: "auto"
            });
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
                    'insertdatetime media table paste code help wordcount',
                    'textcolor'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat '
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

        F.prototype.reindent = function () {
            this.code_mirror.setSelection({
                'line': this.code_mirror.firstLine(),
                'ch': 0,
                'sticky': null
            }, {
                'line': this.code_mirror.lastLine(),
                'ch': 0,
                'sticky': null
            },
                    {scroll: false});
            //auto indent the selection
            this.code_mirror.indentSelection("smart");
            this.code_mirror.setSelection({
                'line': this.code_mirror.firstLine(),
                'ch': 0,
                'sticky': null
            }, {
                'line': this.code_mirror.firstLine(),
                'ch': 0,
                'sticky': null
            },
                    {scroll: false});
            return this;
        };

        F.prototype.setText = function (x, y) {
            y = U.anyBool(y, true);
            x = U.NEString(x, '');
            this.switch_editor(y);
            if (y) {
                this.getRole('editor').val(x);
                this.editor ? this.editor.load() : 0;
            } else {
                this.code_mirror.setValue(x);
                this.code_mirror.refresh();
                this.reindent();
            }

            return this;
        };

        F.prototype.getText = function () {
            if (this.get_check_state()) {
                this.editor ? this.editor.save() : 0;
                return U.NEString(this.getRole('editor').val(), '');
            }
            return U.NEString(this.code_mirror.getValue());
        };

        F.prototype.get_check_state = function () {
            return U.anyBool(this.getRole('check').prop('checked'), true);
        };

        F.prototype.onMonitorCheck = function () {
            var y = U.anyBool(this.getRole('check').prop('checked'), true);
            this.switch_editor(y);
            if (y) {
                this.getRole('editor').val(U.NEString(this.code_mirror.getValue(), ''));
                this.editor ? this.editor.load() : 0;
            } else {
                this.editor ? this.editor.save() : 0;
                this.code_mirror.setValue(U.NEString(this.getRole('editor').val(), ''));
                this.code_mirror.refresh();
                this.reindent();
            }
            return this;
        };

        F.prototype.switch_editor = function (h) {
            this.getRole('check').prop('checked', h);
            if (h) {
                this.getRole('pane-cm').hide();
                this.getRole('pane-mce').show();
            } else {
                this.getRole('pane-mce').hide();
                this.getRole('pane-cm').show();
            }
            return this;
        };

        F.prototype.refresh = function () {
            if (!this.getRole('check').prop('checked')) {
                this.code_mirror.refresh();
                this.reindent();
            }
            return this;
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
<?php
namespace App\Extensions\Form;

use Encore\Admin\Form\Field;

class CKEditor extends Field{

    protected $view = 'admin.ckeditor';

    protected static $js = [
        '/vendor/ckeditor/ckeditor.js',
        '/vendor/ckeditor/adapters/jquery.js'
    ];


    protected $simple = false;

    protected $language = 'en';

    protected $routeName = '';

    protected $browseUrl = '';

    protected $upload  = false;

    public function __construct($column, array $arguments)
    {
        parent::__construct($column, $arguments);
        $this->language = config('app.locale');
    }

    public function simple(){
        $this->simple = true;
        return $this;
    }

    /**
     * Enable upload function and set the upload route
     * @param string $routeName
     * @return $this
     * @internal param string $route
     * @author Ma ShaoQing <mashaoqing@jeulia.net>
     */
    public function upload(string $routeName){
        $this->upload = true;
        $this->routeName = $routeName;
        return $this;
    }

    /**
     * Enable finder function and set the browse route
     * @param string $route
     * @author Ma ShaoQing <mashaoqing@jeulia.net>
     * @return $this
     */
    public function browse(string $route){
        $this->browseUrl = $route;
        return $this;
    }



    public function render()
    {
        $config = $this->getEditorConfig();
        $this->script = <<<SCRIPT
         $('{$this->getElementClassSelector()}').ckeditor({$config});
SCRIPT;
        return parent::render();
    }

    public function getEditorConfig(){
        $toolsConfig = null; $fileBrowserUrlString = null; $fileUploadUrlString = null;
        if ($this->simple===true){
            $toolsConfig = "toolbarGroups : [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'about', groups: [ 'about' ] }
	],\n\r removeButtons : 'Superscript,Styles,Format,About,Blockquote,Outdent,NumberedList,BulletedList,RemoveFormat,Source,Maximize,Table,HorizontalRule,Paste,PasteText,PasteFromWord,Scayt,Anchor,Subscript,Indent,Undo,Redo',";
        }
        if ($this->browseUrl){
            $fileBrowserUrlString = "filebrowserBrowseUrl: '{$this->browseUrl}',";
        }
        if ($this->upload == true){
            $fileUploadUrlString = "filebrowserUploadUrl: '{$this->getUploadUrl('button')}',extraPlugins:'uploadimage',uploadUrl:'{$this->getUploadUrl('drag')}',";
        }

        return <<<EOT
{
            language: '{$this->language}',
            {$toolsConfig}
            {$fileBrowserUrlString}
            {$fileUploadUrlString}
        }
EOT;

    }

    private function getUploadUrl($type)
    {
        if (!empty($this->routeName)){
            return route($this->routeName,array('type'=>$type));
        }
        return false;
    }
}
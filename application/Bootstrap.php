<?php

use Illuminate\Events\Dispatcher as LDispatcher;
use Illuminate\Container\Container as LContainer;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * @name Bootstrap
 * @author longmsdu
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends \Yaf\Bootstrap_Abstract{

	public $config;

    public function _initConfig() {
		//把配置保存起来
		$this->config = \Yaf\Application::app()->getConfig();
		\Yaf\Registry::set('config', $this->config);
	}

	public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(\Yaf\Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(\Yaf\Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
	}

	function _initComposerAutoload(\Yaf\Dispatcher $dispatcher)
	{
		$autoload = APPLICATION_PATH . '/vendor/autoload.php';
		if (file_exists($autoload)) {
			\Yaf\Loader::import($autoload);
		}
	}

	// 初始化 Eloquent ORM
	public function _initDefaultDbAdapter(\Yaf\ Dispatcher $dispatcher)
	{
		$capsule = new Capsule();
		$capsule->addConnection($this->config->database->toArray());
		$capsule->setEventDispatcher(new LDispatcher(new LContainer));
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	}

	public function _initNamespaces(){
		//申明, 凡是以Foo和Local开头的类, 都是本地类
		//\Yaf\Loader::getInstance($this->config->application->library)->registerLocalNameSpace('Foo1');
	}
}

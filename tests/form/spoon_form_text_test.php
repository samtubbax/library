<?php

// spoon charset
if(!defined('SPOON_CHARSET')) define('SPOON_CHARSET', 'utf-8');

// includes
require_once 'spoon/spoon.php';
require_once 'PHPUnit/Framework/TestCase.php';

class SpoonFormTextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var	SpoonForm
	 */
	private $frm;

	/**
	 * @var	SpoonFormText
	 */
	private $txtName;

	public function setup()
	{
		$this->frm = new SpoonForm('textfield');
		$this->txtName = new SpoonFormText('name', 'I am the default value');
		$this->frm->add($this->txtName);
	}

	public function testGetDefaultValue()
	{
		$this->assertEquals('I am the default value', $this->txtName->getDefaultValue());
	}

	public function testErrors()
	{
		$this->txtName->setError('You suck');
		$this->assertEquals('You suck', $this->txtName->getErrors());
		$this->txtName->addError(' cock');
		$this->assertEquals('You suck cock', $this->txtName->getErrors());
		$this->txtName->setError('');
		$this->assertEquals('', $this->txtName->getErrors());
	}

	public function testAttributes()
	{
		$this->txtName->setAttribute('rel', 'bauffman.jpg');
		$this->assertEquals('bauffman.jpg', $this->txtName->getAttribute('rel'));
		$this->txtName->setAttributes(array('id' => 'specialID'));
		$this->assertEquals(array('name' => 'name', 'class' => 'inputTextfield', 'rel' => 'bauffman.jpg', 'id' => 'specialID'), $this->txtName->getAttributes());
	}

	public function testIsFilled()
	{
		$this->assertEquals(false, $this->txtName->isFilled());
		$_POST['name'] = 'I am not empty';
		$this->assertEquals(true, $this->txtName->isFilled());
	}

	public function testIsAlphabetical()
	{
		$this->assertEquals(false, $this->txtName->isAlphabetical());
		$_POST['name'] = 'Bauffman';
		$this->assertEquals(true, $this->txtName->isAlphabetical());
	}

	public function testIsAphaNumeric()
	{
		$_POST['name'] = 'Spaces are not allowed?';
		$this->assertEquals(false, $this->txtName->isAlphaNumeric());
		$_POST['name'] = 'L33t';
		$this->assertEquals(true, $this->txtName->isAlphaNumeric());
	}

	public function testIsBetween()
	{
		$_POST['name'] = '101';
		$this->assertEquals(true, $this->txtName->isBetween(1, 102));
		$this->assertEquals(true, $this->txtName->isBetween(-101, 101));
		$this->assertEquals(false, $this->txtName->isBetween(200, 201));
		$this->assertEquals(false, $this->txtName->isBetween(1000, 200));
	}

	public function testIsBool()
	{
		$_POST['name'] = 'true';
		$this->assertEquals(true, $this->txtName->isBool());
		$_POST['name'] = '1';
		$this->assertEquals(true, $this->txtName->isBool());
		$_POST['name'] = 'false';
		$this->assertEquals(true, $this->txtName->isBool());
		$_POST['name'] = '0';
		$this->assertEquals(true, $this->txtName->isBool());
		$_POST['name'] = 'I liek boobies';
		$this->assertEquals(false, $this->txtName->isBool());
		$_POST['name'] = '101';
		$this->assertEquals(true, $this->txtName->isBool());
		$_POST['name'] = '090';
		$this->assertEquals(false, $this->txtName->isBool());
	}

	public function testIsDigital()
	{
		$_POST['name'] = '090';
		$this->assertEquals(true, $this->txtName->isDigital());
		$_POST['name'] = 'Douchebag';
		$this->assertEquals(false, $this->txtName->isDigital());
		$_POST['name'] = '';
		$this->assertEquals(false, $this->txtName->isDigital());
	}

	public function testIsEmail()
	{
		$this->assertEquals(false, $this->txtName->isEmail());
		$_POST['name'] = 'davy@spoon-library.be';
		$this->assertEquals(true, $this->txtName->isEmail());
		$_POST['name'] = '';
		$this->assertEquals(false, $this->txtName->isEmail());
	}

	public function testIsFilename()
	{
		$this->assertEquals(false, $this->txtName->isFilename());
		$_POST['name'] = 'something.jpg';
		$this->assertEquals(true, $this->txtName->isFilename());
	}

	public function testIsFloat()
	{
		$this->assertEquals(false, $this->txtName->isFloat());
		$_POST['name'] = 1.1;
		$this->assertEquals(true, $this->txtName->isFloat());
		$_POST['name'] = -209;
		$this->assertEquals(true, $this->txtName->isFloat());
		$_POST['name'] = 199;
		$this->assertEquals(true, $this->txtName->isFloat());
	}

	public function testIsGreatherThan()
	{
		$_POST['name'] = 199;
		$this->assertEquals(true, $this->txtName->isGreaterThan(1));
		$this->assertEquals(true, $this->txtName->isGreaterThan(-199));
		$this->assertEquals(false, $this->txtName->isGreaterThan(199));
	}

	public function testIsInteger()
	{
		$_POST['name'] = 199;
		$this->assertEquals(true, $this->txtName->isInteger());
		$_POST['name'] = -199;
		$this->assertEquals(true, $this->txtName->isInteger());
		$_POST['name'] = 1.1;
		$this->assertEquals(false, $this->txtName->isInteger());
		$_POST['name'] = '1,9';
		$this->assertEquals(false, $this->txtName->isInteger());
	}

	public function testIsIp()
	{
		$this->assertEquals(false, $this->txtName->isIp());
		$_POST['name'] = '127.0.0.1';
		$this->assertEquals(true, $this->txtName->isIp());
		$_POST['name'] = '192.168.1.101';
		$this->assertEquals(true, $this->txtName->isIp());
	}

	public function testIsMaximum()
	{
		$_POST['name'] = 'Spanks';
		$this->assertEquals(true, $this->txtName->isMaximum(10));
		$_POST['name'] = 199;
		$this->assertEquals(false, $this->txtName->isMaximum(18));
		$this->assertEquals(true, $this->txtName->isMaximum(300));
	}

	public function testIsMaximumCharacters()
	{
		$_POST['name'] = 'Writing tests can be pretty frakkin boring';
		$this->assertEquals(true, $this->txtName->isMaximumCharacters(100));
		$this->assertEquals(false, $this->txtName->isMaximumCharacters(10));
	}

	public function testIsMinimum()
	{
		$_POST['name'] = 5;
		$this->assertEquals(true, $this->txtName->isMinimum(5));
		$this->assertEquals(true, $this->txtName->isMinimum(4));
		$this->assertEquals(false, $this->txtName->isMinimum(7));
	}

	public function testIsMinimumCharacters()
	{
		$_POST['name'] = 'Stil pretty bored';
		$this->assertEquals(true, $this->txtName->isMinimumCharacters(10));
		$this->assertEquals(true, $this->txtName->isMinimumCharacters(2));
		$this->assertEquals(false, $this->txtName->isMinimumCharacters(23));
	}

	public function testIsNumeric()
	{
		$_POST['name'] = '010192029';
		$this->assertEquals(true, $this->txtName->isNumeric());
		$_POST['name'] = '1337';
		$this->assertEquals(true, $this->txtName->isNumeric());
		$_POST['name'] = 'I can haz two cheezeburgers?';
		$this->assertEquals(false, $this->txtName->isNumeric());
	}

	public function testIsSmallerThan()
	{
		$_POST['name'] = 137;
		$this->assertEquals(true, $this->txtName->isSmallerThan(138));
		$this->assertEquals(true, $this->txtName->isSmallerThan(200));
		$this->assertEquals(false, $this->txtName->isSmallerThan(0));
		$this->assertEquals(false, $this->txtName->isSmallerThan(-16));
	}

	public function testIsURL()
	{
		$_POST['name'] = 'http://www.spoon-library.be';
		$this->assertEquals(true, $this->txtName->isURL());
		$_POST['name'] = 'http://127.0.0.1';
		$this->assertEquals(true, $this->txtName->isURL());
	}

	public function testIsValidAgainstRegexp()
	{
		$_POST['name'] = 'Spoon';
		$this->assertEquals(true, $this->txtName->isValidAgainstRegexp('/([a-z]+)/'));
		$this->assertEquals(false, $this->txtName->isValidAgainstRegexp('/([0-9]+)/'));
	}

	public function testGetValue()
	{
		$_POST['form'] = 'textfield';
		$_POST['name'] = '<a href="http://www.spoon-library.be">Bobby Tables, my friends call mééé</a>';
		$this->assertEquals(SpoonFilter::htmlspecialchars($_POST['name']), $this->txtName->getValue());
		$this->assertEquals($_POST['name'], $this->txtName->getValue(true));
	}
}

?>
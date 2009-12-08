<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace lithium\tests\cases\g11n;

use \lithium\core\Environment;
use \lithium\g11n\Message;
use \lithium\g11n\Catalog;
use \lithium\g11n\catalog\adapters\Memory;

class MessageTest extends \lithium\test\Unit {

	protected $_backups = array();

	public function setUp() {
		$this->_backups['catalogConfig'] = Catalog::config()->to('array');
		Catalog::reset();
		Catalog::config(array(
			'runtime' => array('adapter' => new Memory())
		));
	}

	public function tearDown() {
		Catalog::reset();
		Catalog::config($this->_backups['catalogConfig']);
	}

	public function testTranslate() {
		$data = array(
			'root' => function($n) { return $n == 1 ? 0 : 1; }
		);
		Catalog::write('message.plural', $data, array('name' => 'runtime'));

		$data = array(
			'de' => array(
				'lithium' => 'Kuchen',
				'house' => array('Haus', 'Häuser')
		));
		Catalog::write('message.page', $data, array('name' => 'runtime'));

		$expected = 'Kuchen';
		$result = Message::translate('lithium', array('locale' => 'de'));
		$this->assertEqual($expected, $result);

		$expected = 'Haus';
		$result = Message::translate('house', array('locale' => 'de'));
		$this->assertEqual($expected, $result);

		$expected = 'Häuser';
		$result = Message::translate('house', array('locale' => 'de', 'count' => 5));
		$this->assertEqual($expected, $result);
	}
}

?>
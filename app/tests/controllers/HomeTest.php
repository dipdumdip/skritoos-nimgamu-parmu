<?php
/*
class HomeTest extends TestCase {


	public function testHomePage_display()
	{
	 	$responds=$this->call('GET','/');
	 	$crawler=$this->client->request('GET','/');

	 	$found= $crawler->filter("body:contains('Free Online Tool For Stock')");

	 	// $this->assertEquals('Free Online Tool For Stock', $responds->getContent());
	 	// $this->assertTrue(strpos($responds->getContent(), 'Free Online Tool For Stock') !==false);
	 	// $this->assertGreaterThan(0, count($found), 'Expected to see the text inside body tag');
	 	$this->see('Free Online Tool For Stock', 'html');
	 	// var_dump($responds->getContent());
	}

	protected function see($search, $tag='body'){

		$crawler=$this->client->getCrawler();

		$found= $crawler->filter("{$tag}:contains('{$search}')");

		$this->assertGreaterThan(0, count($found), 'Expected to see the {$search} inside body tag');

	}

}
*/
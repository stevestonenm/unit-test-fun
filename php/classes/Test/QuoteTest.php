<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 12/4/16
 * Time: 11:59 PM
 */

namespace Edu\Cnm\Quote\Test;

use Edu\Cnm\Quote\{
	Quote
};


require_once(dirname(__DIR__) . "/autoload.php");

/**
 * unit test for the Quote Class
 * PDO methods are located in the Quote class
 * @ see php/classes/Quote.php
 * @author George Kephart
 */
class QuoteTest extends QuoteTestSetup {
	/**
	 * protect Constant for the actual quote that is being used to test the quote class
	 * @var string $VALID_GREAT_QUOTE
	 */
	protected $VALID_GREAT_QUOTE = "I don't think so";

	/**
	 * protect Constant for the actual quote that is being used to test update the quote class
	 * @var string $VALID_GREAT_QUOTE1
	 */
	protected $VALID_GREAT_QUOTE1 = "Just tried watching Saturday Night Live - unwatchable! Sad";

	/**
	 * protected constant for the person who is responsible for the quote
	 * @var  string $VALID_QUOTE_AUTHOR
	 */
	protected $VALID_QUOTE_AUTHOR = "goobldigupeguck";

	/**
	 * protected constant for the person who posted the quote
	 * @var  string $VALID_QUOTE_POSTER
	 */
	protected $VALID_QUOTE_POSTER = "SenatorArlo";

	/**
	 * protected constant for the rating of the quote
	 * @var  int $VALID_QUOTE_RATING
	 */
	protected $VALID_QUOTE_RATING = 3364;

	/**
	 * protected constant for the rating of the quote
	 * @var  string $VALID_QUOTE_RANKING1
	 */
	protected $VALID_QUOTE_RATING1 = 223;

	/**
	 * protected constant for the rating of the quote
	 * @var  string $VALID_QUOTE_RANKING2
	 */

	/**
	 * protected constant for the rating of the quote
	 */
	protected $VALID_QUOTE_RATING2 = 332;

	/**
	 * create all dependent objects so that the test can run properly
	 */
	public final function setUp() {
		// run the setup method so the test can run properly
		// this is where all dependencies would be squashed so the test could be run properly.
		parent::setUp();
	}

	/**
	 * preform the actual insert method and enforce that is meets expectations I.E corrupted data is worth nothing
	 */

	public function testValidQuoteInsert() {
		$numRows = $this->getConnection()->getRowCount("quote");

		//create the quote object
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_AUTHOR,  $this->VALID_QUOTE_RATING);
		//insert the quote object
		$quote->insert($this->getPDO());

		//grab the data from MySQL and enforce that it meets expectations
		$pdoQuote = Quote::getQuoteByQuoteId($this->getPDO(), $quote->getQuoteId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("quote"));
		$this->assertEquals($pdoQuote->getQuoteId(), $quote->getQuoteId());
		$this->assertEquals($pdoQuote->getQuote(), $quote->getQuote());
		$this->assertEquals($pdoQuote->getQuoteAuthor(), $quote->getQuoteAuthor());
		$this->assertEquals($pdoQuote->getQuotePoster(), $quote->getQuotePoster());
		$this->assertEquals($pdoQuote->getQuoteRating(), $quote->getQuoteRating());
	}

	/**
	 * preform an invalid insert method I.E gotta test those exceptions
	 * @expectedException \PDOException
	 */
	public function testInvalidQuoteInsert() {
		//create a invalid quote object and try and insert it into the database
		$quote = new Quote(QuoteTestSetup::INVALID_KEY, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);
		$quote->insert($this->getPDO());
	}

	/**
	 * create a quote object, update it in the database, and then enforce that it meets expectations
	 */
	public function testValidQuoteUpdate() {
		//grab the number of vows and save it for the test
		$numRows = $this->getConnection()->getRowCount("quote");

		//create the quote object then insert it
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_RATING);
		$quote->insert($this->getPDO());

		//edit the quote object then insert the object back into the database
		$quote->setQuoteRating($this->VALID_QUOTE_RATING1);
		$quote->update($this->getPDO());


		//grab the quote out of the database and enforce the object meets expectations
		$pdoQuote = Quote::getQuoteByQuoteId($this->getPDO(), $quote->getQuoteId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("quote"));
		$this->assertEquals($pdoQuote->getQuoteId(), $quote->getQuoteId());
		$this->assertEquals($pdoQuote->getQuote(), $quote->getQuote());
		$this->assertEquals($pdoQuote->getQuoteAuthor(), $quote->getQuoteAuthor());
		$this->assertEquals($pdoQuote->getQuotePoster(), $quote->getQuotePoster());
		$this->assertEquals($pdoQuote->getQuoteRating(), $quote->getQuoteRating());
	}

	/**
	 * try and update a quote that does not exist
	 * @expectedException \PDOException
	 */
	public function testInvalidQuoteUpdate() {
		//create the quote object t
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);
		//try and update a quote object that does not exist.
		$quote->update($this->getPDO());
	}

	/**
	 * create a quote object, delete it, then enforce that it was deleted
	 */
	public function testValidQuoteDelete() {
		//grab the number of vows and save it for the test
		$numRows = $this->getConnection()->getRowCount("quote");

		//create the quote object
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);

		//insert the quote object
		$quote->insert($this->getPDO());

		//delete the quote from the database
		$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("quote"));
		$quote->delete($this->getPDO());

		//enforce that the deletion was successful
		$pdoQuote = Quote::getQuoteByQuoteId($this->getPDO(), $quote->getQuoteId());
		$this->assertNull($pdoQuote);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("quote"));
	}

	/**
	 * try and delete a quote object that does not exist
	 * @expectedException \PDOException
	 */

	public function testInvalidQuoteDelete() {
		//create the quote object
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);

		//try and delete the object without putting it into the database
		$quote->delete($this->getPDO());
	}

	/**
	 * try and grab a quote by a primary that does not exist
	 */

	public function testInvalidGetByQuoteId() {
		//grab the quote by an invalid key
		$quote = Quote::getQuoteByQuoteId($this->getPDO(), QuoteTestSetup::INVALID_KEY);
		$this->assertEmpty($quote);

	}

	/**
	 * insert a quote object, grab it by the authors name , and enforce that it meets expectations
	 */
	public function testValidGetQuoteByAuthor() {

		$numRows = $this->getConnection()->getRowCount("quote");
		//create a quote object and insert it into the database
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);

		//insert the quote into the database
		$quote->insert($this->getPDO());

		//grab the quote from the database.
		$pdoQuote = Quote::getQuoteByAuthor($this->getPDO(), $quote->getQuoteAuthor());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("quote"));

		$this->assertEquals($pdoQuote->getQuoteId(), $quote->getQuoteId());
		$this->assertEquals($pdoQuote->getQuote(), $quote->getQuote());
		$this->assertEquals($pdoQuote->getQuoteAuthor(), $quote->getQuoteAuthor());
		$this->assertEquals($pdoQuote->getQuotePoster(), $quote->getQuotePoster());
		$this->assertEquals($pdoQuote->getQuoteRating(), $quote->getQuoteRating());
	}


	/**
	 * try and grab the quote by an author that does not exist
	 */
	public function testInvalidGetByAuthor() {
		$quote = Quote::getQuoteByAuthor($this->getPDO(), "birdie Bernie");
		$this->assertEmpty($quote);
	}

	/**
	 * insert a quote, use getALl method, then enforce it meets expectatin
	 */
	public function testGetAllQuotes() {
		$numRows = $this->getConnection()->getRowCount("quote");

		//insert the quote into the database
		$quote = new Quote(null, $this->VALID_GREAT_QUOTE, $this->VALID_QUOTE_AUTHOR, $this->VALID_QUOTE_POSTER, $this->VALID_QUOTE_RATING);

		//insert the quote into the database
		$quote->insert($this->getPDO());

		//grab the results from mySQL and enforce it meets expectations
		$results = Quote::getAllQuotes($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("quote"));
		$this->assertCount(1, $results);
		//$this->assertContainsOnlyInstancesOf()

		//grab the results from the array and make sure it meets expectations
		$pdoQuote = $results[0];
		//$this->assertEquals($pdoQuote->getQuoteId(), $quote->getQuoteId());
		$this->assertEquals($pdoQuote->getQuote(), $quote->getQuote());
		$this->assertEquals($pdoQuote->getQuoteAuthor(), $quote->getQuoteAuthor());
		$this->assertEquals($pdoQuote->getQuotePoster(), $quote->getQuotePoster());
		$this->assertEquals($pdoQuote->getQuoteRating(), $quote->getQuoteRating());


	}
}






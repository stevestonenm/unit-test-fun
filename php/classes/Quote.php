<?php

namespace Edu\Cnm\Quote;

require_once("autoload.php");


/**
 * Created by PhpStorm.
 * User: George
 * Date: 11/23/16
 * Time: 9:40 PM
 */
class Quote implements \JsonSerializable {
	/**
	 * primary key of the quote
	 * @var int $quoteId
	 **/

	private $quoteId;
	/**
	 * the actual quote that was created
	 * @var string $quote
	 */
	private $quote;

	/**
	 * the person responsible for  the quote
	 * $quoteAuthor string $quoteAuthor
	 */

	private $quoteAuthor;

	/**
	 * the person who posted the quote
	 * $quotePoster
	 */

	private $quotePoster;

	/**
	 * the end user rank of the quote
	 * $quoteRank
	 */

	private $quoteRating;

	/**
	 * constructor I.E the method that creates the quote object.
	 *
	 * @param int|null $newQuoteId id of the quote  or null if the task is a new insert (new means it went through the mutators)
	 * cant trust the incompetent end users)
	 * @param string $newQuote the mutated entry for the actual quote
	 * @param string $newQuoteAuthor the mutated entry for the actual quote author
	 * @param string $newQuotePoster the person who posted the quote after mutation
	 * @param int $newQuoteRating the ranking of the quote after needed mutation
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

	public function __construct(?int $newQuoteId, string $newQuote, string $newQuoteAuthor, string $newQuotePoster, int $newQuoteRating) {
		try {
			$this->setQuoteId($newQuoteId);
			$this->setquote($newQuote);
			$this->setQuoteAuthor($newQuoteAuthor);
			$this->setQuotePoster($newQuotePoster);
			$this->setQuoteRating($newQuoteRating);

		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

			//determine what exception type was thrown. Then throw the exception
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

	}

	/**
	 * accessor method for quoteId. Since the accessor is a get no sanitation of input is needed
	 * @return int quoteId the main identifier for a specific quote object
	 */

	public function getQuoteId(): int {
		return ($this->quoteId);
	}

	/**
	 * mutator method for the quoteId.
	 * @param int|null $newQuoteId value of the primary key for the quote id.
	 * @throws \RangeException if the key is negative (I.E the key does not meet expectation).
	 */
	public function setQuoteId(int $newQuoteId = null): void {
		//Checks to see if the key is null. If it is null it is a new object and needs to be inserted  into the database.
		if($newQuoteId === null) {
			$this->quoteId = null;
			return;
		}

		// Enforce that the key is positive, if not throw a range exception.
		if($newQuoteId <= 0) {
			throw (new \RangeException("the quoteId is not positive or insecure"));
		}

		$this->quoteId = $newQuoteId;
	}

	/** accessor method for quote, since the accessor directly comunicates with the database no sanitation is needed
	 * @return string $quote the actual quote that was posted
	 */
	public function getQuote(): string {
		return $this->quote;
	}

	/**
	 * Mutator method for the great quote  I.E the actual quote
	 * @param $newQuote string value for the quote in question
	 * @throws \InvalidArgumentException: Exception thrown if the quote is insecure.
	 * @throws \RangeException: Exception thrown if the quote wont fit in the database.
	 */

	public function setQuote(string $newQuote): void {

		$newQuote = trim($newQuote);
		$newQuote = filter_var($newQuote, FILTER_SANITIZE_STRING);
		if(empty($newQuote) === true) {
			throw (new \InvalidArgumentException("quote is empty or insecure"));
		}
		if(strlen($newQuote) > 256) {
			throw(new \RangeException("quote is too long"));
		}
		$this->quote = $newQuote;
	}

	/**
	 * accessor method for quote author, since the accessor method directly communicates with the database no sanitation is needed.
	 * @return string $quoteAuthor the actual author from the database
	 *
	 */
	public function getQuoteAuthor(): string {
		return $this->quoteAuthor;
	}

	/**
	 * mutator method for the quote author I.E the person the quote is attributed to.
	 * @param string $newQuoteAuthor string value of the quoteAuthor in the object
	 * @throws \InvalidArgumentException: Exception thrown if the quote is insecure.
	 * @throws \RangeException: Exception thrown if the quote wont fit in the database.
	 */
	public function setQuoteAuthor(string $newQuoteAuthor): void {
		$newQuoteAuthor = trim($newQuoteAuthor);
		$newQuoteAuthor = filter_var($newQuoteAuthor, FILTER_SANITIZE_STRING);
		if(empty($newQuoteAuthor) === true) {
			throw(new \InvalidArgumentException("quoteAuthor is empty or insecure"));
		}
		if(strlen($newQuoteAuthor) > 64) {
			throw(new \RangeException("quoteAuthor is too long"));
		}
		$this->quoteAuthor = $newQuoteAuthor;
	}


	/**
	 * Accessor method for quote poster,since the accessor method directly communicates with the database no sanitation is needed.
	 * @return string $quotePoster the person who posted the quote
	 */
	public function getQuotePoster(): string {
		return $this->quotePoster;
	}

	/**
	 * mutator method for quotePoster I.E person who posted the quote
	 * @param string $newQuotePoster
	 * @throws \InvalidArgumentException: Exception thrown if the quote is insecure.
	 * @throws \RangeException: Exception thrown if the quote wont fit in the database.
	 */
	public function setQuotePoster(string $newQuotePoster): void {
		$newQuotePoster = trim($newQuotePoster);
		$newQuotePoster = filter_var($newQuotePoster, FILTER_SANITIZE_STRING);
		if(empty($newQuotePoster) === true) {
			throw(new \InvalidArgumentException());
		}
		if(strlen($newQuotePoster) > 64) {
			throw(new \InvalidArgumentException("quotePoster is too long"));
		}
		$this->quotePoster = $newQuotePoster;
	}
	/**
	 * accesor method for the rank of the quote, since the accessor method directly communicates with the database no sanitation is needed.
	 * @return int $quoteRank the rank of the quote.
	 */
	public function getQuoteRating() : int  {
		return $this->quoteRating;
	}

	/**
	 * rating for the quote in question
	 *
	 * mutator method for the quoteRank I.e rank of the quote
	 * @param int $newQuoteRating
	 */
	public function setQuoteRating(int $newQuoteRating): void {
		if($newQuoteRating <= 0) {
			throw(new \RangeException("rating value is not positive"));
		}
		$this->quoteRating = $newQuoteRating;
	}


	/**
	 * insert method for quote I.E inserts the quote into the database
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError thrown if $pdo is not a connection object
	 */
	public function insert(\PDO $pdo): void {
		if($this->quoteId !== null) {
			throw(new \PDOException("not a quote"));
		}

		//create a query template
		$query = "INSERT INTO quote ( quote, quoteAuthor, quotePoster, quoteRating) VALUES (:quote, :quoteAuthor, :quotePoster, :quoteRating)";
		$statement = $pdo->prepare($query);
		//bind the member variables to the placeholder template
		$parameters = ["quote" => $this->quote, "quoteAuthor" => $this->quoteAuthor, "quotePoster" => $this->quotePoster, "quoteRating" => $this->quoteRating];
		$statement->execute($parameters);
		$this->quoteId = intval($pdo->lastInsertId());
	}

	/**
	 * update method for quote I.E updates the existing quote in the database
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError thrown if $pdo is not a connection object
	 */

	public function update(\PDO $pdo): void {
		if($this->quoteId === null) {
			throw(new \PDOException("cannot update a non existing quote"));
		}

		//create a query template
		$query = "UPDATE quote SET quoteId = :quoteId, quote = :quote, quoteAuthor = :quoteAuthor, quotePoster = :quotePoster, quoteRating = :quoteRating";
		$statement = $pdo->prepare($query);

		//Bind the member variables to placeholder template
		$parameters = ["quote" => $this->quote, "quoteAuthor" => $this->quoteAuthor, "quotePoster" => $this->quotePoster, "quoteRating" => $this->quoteRating, "quoteId" => $this->quoteId];
		//execute the actual udate
		$statement->execute($parameters);
	}

	/**
	 * delete method for quote I.E deletes the existing quote in the database
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError thrown if $pdo is not a connection object
	 */

	public function delete(\PDO $pdo): void {
		if($this->quoteId === null) {
			throw(new \PDOException("cannot delete a non existing quote"));
		}

		//create a query template
		$query = "DELETE FROM quote WHERE quoteId = :quoteId";
		$statement = $pdo->prepare($query);

		//bind member variable to placeholder
		$parameter = ["quoteId" => $this->quoteId];
		$statement->execute($parameter);
	}

	/**
	 * get the quote by the quoteId. since the quoteId is unique only one value will be returned
	 * @param \PDO $pdo PDO connection object
	 * @param int $quoteId Id of the quote that is trying to be grabbed from the database
	 * @returns Quote|null  returns quote if found or returns null
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if variables are not the correct type
	 */

	public static function getQuoteByQuoteId(\PDO $pdo, int $quoteId): ?Quote {
		//enforce the quoteId is positive | secure
		if($quoteId <= 0) {
			throw (new \PDOException("quote id is not positive"));
		}
		//create the query template: the query enforces only one value is returned
		$query = "SELECT quoteId, quote, quoteAuthor, quotePoster, quoteRating FROM quote WHERE quoteId = :quoteId";
		$statement = $pdo->prepare($query);

		//bind the quoteId to a placeholder in the template
		$parameters = ["quoteId" => $quoteId];
		$statement->execute($parameters);

		//Grab the quote from mySQL
		try {
			$quote = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$quote = new Quote($row["quoteId"], $row["quote"], $row["quoteAuthor"], $row["quotePoster"], $row["quoteRating"]);
			}
		} catch(\Exception $exception) {
			// If row cannot be converted rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($quote);
	}

	/**
	 * gets all of the quotes from the same author
	 * @param \PDO $pdo PDO connection object
	 * @param string $quoteAuthor parameter used to search for like content in the database
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if variables are not the correct type
	 * @return \SplFixedArray of quote objects that meet the author search Criteria
	 */

	public static function getQuoteByAuthor(\PDO $pdo, string $quoteAuthor): \SplFixedArray {
		//enforce the string is secure or toss it
		$quoteAuthor = trim($quoteAuthor);
		$quoteAuthor = filter_var($quoteAuthor, FILTER_SANITIZE_STRING);
		if(empty($quoteAuthor) === true) {
			throw(new \PDOException("the search criteria are insecure "));
		}

		//create query template: LIKE in the query allows for multiple objects to be returned.
		$query = "SELECT quoteId, quote, quoteAuthor, quotePoster, quoteRating  FROM quote WHERE quoteAuthor LIKE :quoteAuthor";
		$statement = $pdo->prepare($query);

		//search for given criteria
		$quoteAuthor = "%$quoteAuthor";
		$parameters = ["quoteAuthor" => $quoteAuthor];
		$statement->execute($parameters);

		//build an array of the quote objects that meet search criteria
		$quotes = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$quote = new Quote($row["quoteId"], $row["quote"], $row["quoteAuthor"], $row["quotePoster"], $row["quoteRating"]);
				$quotes[$quotes->key()] = $quote;
				$quotes->next();
			} catch(\Exception $exception) {
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($quotes);
	}

	/**
	 * gets all quotes I.E returns the whole quote table
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @returns \SplFixedArray of all quote objects from the database
	 */
	public static function getAllQuotes(\PDO $pdo): \SplFixedArray {
		//create query template ( SELECT FROM design pattern returns everything in table)
		$query = "SELECT quoteId, quote, quoteAuthor, quotePoster, quoteRating FROM quote";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//build an array of the quote objects that meet search criteria
		$quotes = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while($row = $statement->fetch() !== false) {
			try {
				$quote = new Quote($row["quoteId"], $row["quote"], $row["quoteAuthor"], $row["quotePoster"], $row["quoteRating"]);
				$quotes[$quotes->key()] = $quote;
				$quotes->next();
			} catch(\Exception $exception) {
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($quotes);

	}

	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return ($fields);
	}
}

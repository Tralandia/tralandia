Otazky / ulohy


Exception:
throw new \Nette\UnexpectedValueException('toto mi nesedi');

Rental - este dorobit:

Otazky:
- 

Current status:



	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $email;

	/**
	 * @var phone
	 * @ORM\Column(type="phone")
	 */
	protected $phone;

	/**
	 * @var url
	 * @ORM\Column(type="url")
	 */
	protected $url;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $skype;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

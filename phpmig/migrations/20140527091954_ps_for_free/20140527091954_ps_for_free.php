<?php


class PsForFree extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		$this->executeSqlFromFile('up_start');

		$lean = $this->getLean();


		$query = "select concat(r.slug,'.',d.domain) url, d.domain domain, r.id id from rental r
inner join contact_address a on a.id = r.address_id
inner join location l on l.id = a.primaryLocation_id
inner join domain d on d.id = l.domain_id
where (r.personalSiteUrl is null or r.personalSiteUrl = '')";
		$query = $lean->query($query);

		$usedSlugs = [];
		$insert = [];
		$i = 0;
		foreach($query as $value) {
			$url = $value['url'];
			$id = $value['id'];
			$domain = $value['domain'];
			if(isset($usedSlugs[$url])) {
				$url = "$id.$domain";
			} else {
				$usedSlugs[$url] = $id;
			}

			$insert[] = [
				'url' => $url,
				'template' => 'second',
				'created' => '2014-05-27 12:34:56',
				'updated' => '2014-05-27 12:34:56',
			];

			if($i > 4000) {
				$insert = array_merge(['INSERT INTO personalsite_configuration'], $insert);
				call_user_func_array([$lean, 'query'], $insert);
				$insert = [];
				$i = 0;
			}

			$i++;
		}

		if(count($insert)) {
			$insert = array_merge(['INSERT INTO personalsite_configuration'], $insert);
			call_user_func_array([$lean, 'query'], $insert);
		}



		$this->executeSqlFromFile('up_end');
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}

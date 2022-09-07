<?php

namespace Drupal\character_data;

use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
use \Drupal\Core\File\FileSystemInterface;

class characterService {

  public function CharacterData() {
    	$url = 'https://breakingbadapi.com/api/characters';
		$client = \Drupal::httpClient();
		$request = $client->get($url);
		$body = $request->getBody()->getContents();
		$AllData = (array)json_decode($body);

		foreach ($AllData as $key => $value) {
			$data = file_get_contents($value->img);
			$destination = 'public://'.$value->nickname.'.png';
			$fileRepository = \Drupal::service('file.repository');
			$file = $fileRepository->writeData($data, $destination, FileSystemInterface::EXISTS_REPLACE);
			$node = Node::create([
			  'type'=> 'characters',
			  'title'=> $value->name,
			  'field_character_id'=> $value->char_id,
			  'field_character_status'=> $value->status,
			  'field_character_nickname'=> $value->nickname,
			  'field_character_portrayed'=> $value->portrayed,
			  'field_character_category'=> $value->category,
			  'field_character_birthday'=> $value->birthday,
			  'field_character_occupation'=> $value->category,
			  'field_character_appearance'=> $value->occupation,
			  'field_character_image'=> [
		      'target_id' => $file->id(),
		      'alt' => 'Sample',
		      'title' => 'nodecreate Image'
			  ],
			]);
			$node->save();	
		}


		$message = "Node created !";
		return \Drupal::logger('character_data')->notice($message);
  }

}


<?php


class ContentDAO {
    private static $TABLE_NAME = 'Contents';
    private $client;
    
    public function __construct ($dynamoClient) {
        $this->client = $dynamoClient;
    }
    
    // children = array of [email, date]
    public function create ($content, $children) {
        $this->client->putItem(array(
            'TableName' => ContentDAO::$TABLE_NAME,
            'Item' => array(
                'name'    => array('S' => $content->getName()),
                'owner'   => array('S' => $content->getEmailOwner()),
                'url'     => array('S' => $content->getUrl()),
                'type'    => array('S' => $content->getType())
                )
        ));
        $length = count($children);
        $childDAO = new ChildDAO(DynamoDbClientBuilder::get());
        for ($i = 0; $i < $length; $i++) {
            $email = $children[$i]['email'];
            $child = $childDAO->get($email);
            if ($child != null) {
                $child->addContent(
                        $content,
                        $children[$i]['date']); // date
                $childDAO->update($child);
            }
        }
    }
    
    //méthode à tester
    public function get($name, $owner) {
        $dto = $this->client->getItem(array(
            'ConsistentRead' => true,
            'TableName' => ContentDAO::$TABLE_NAME,
            'Key' => array(
                'name' => array('S' => $name),
                'owner' => array('S' => $owner)
            )
        ));
        $content = new Content();
        $content->setName($dto['Item']['name']['S']);
        $content->setType($dto['Item']['type']['S']);
        $content->setEmailOwner($dto['Item']['owner']['S']);
        $content->setUrl($dto['Item']['url']['S']);
        return $content;
    }
}
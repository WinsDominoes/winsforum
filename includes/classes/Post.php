<?php
    class Post {
        
        private $con, $sqlData;
        
        public function __construct($con, $input) {
            $this->con = $con;
        
            if(is_array($input)) {
                $this->sqlData = $input;
            }
            else {
                $query = $this->con->prepare("SELECT * FROM posts WHERE post_url = :url");
                $query->bindParam(":url", $input);
                $query->execute();
    
                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function getPostId() {
            return $this->sqlData["post_id"];
        }

        public function getPostUrl() {
            return $this->sqlData["post_url"];
        }

        public function getPostContent() {
            return $this->sqlData["post_content"];
        }

        public function getPostAuthor() {
            return $this->sqlData["post_author"];
        }

        public function getPostedDate() {
            $rawDate = $this->sqlData["post_timestamp"];
            return date('M j Y g:i A', strtotime($rawDate));
        }
    }
?>
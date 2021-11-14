<?php
    class Comment {
        
        private $con, $sqlData;
        
        public function __construct($con, $input) {
            $this->con = $con;
        
            if(is_array($input)) {
                $this->sqlData = $input;
            }
            else {
                $query = $this->con->prepare("SELECT * FROM comments WHERE comment_post_url = :post_url");
                $query->bindParam(":post_url", $input);
                $query->execute();
    
                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }

        public function getCommentId() {
            return $this->sqlData["comment_id"];
        }

        public function getCommentPostId() {
            return $this->sqlData["command_post_url"];
        }

        public function getCommentUrl() {
            return $this->sqlData["comment_url"];
        }

        public function getCommentContent() {
            return $this->sqlData["comment_content"];
        }

        public function getCommentAuthor() {
            return $this->sqlData["comment_author"];
        }

        public function getCommentPostedDate() {
            $rawDate = $this->sqlData["comment_timestamp"];
            return date('M j Y g:i A', strtotime($rawDate));
        }
    }
?>
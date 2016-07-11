<?php
class Sort {
    /*
    Compares votes on classes and returns in descending order.
    */
    public function sort_by_vote($data) {
        usort($data, 'self::dovotesort');
        return $data;
    }
    
    /*
    Performs string comparison on titles and returns in ascending order.
    */
    public function sort_by_name($data) {
        usort($data, 'self::donamesort');
        return $data;
    }
  
    /*
    User sort function (usort) callback for sort_by_vote.
    */
    protected function dovotesort($a, $b) {
        if ( $a->votes < $b->votes ) {
            return $a->votes;
        }
    }
    
    /*
    User sort function (usort) callback for sort_by_name.
    */
    protected function donamesort($a, $b) {
        return strcmp($a->title, $b->title);
    }
}
?>
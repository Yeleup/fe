<?php
class ModelFeTdcatalogNodes extends Model {

    public function getByModificationId($modification_id, $parent_id = null) {
        // return $this->getByModificationIdLocal($modification_id, $parent_id);
        return $this->getByModificationIdWhereNoChildren($modification_id);
    }



    private function getByModificationIdLocal($modification_id, $parent_id = null) {
        $sql = "SELECT *
            FROM " . DB_PREFIX . "fe_td_node
            WHERE
            modification_id = '" . (int)$modification_id . "'";

        if ($parent_id === null) {
            $sql .= " AND parent_id is NULL";
        } else {
            $sql .= " AND parent_id = '" . (int)$parent_id . "'";
        }

        $result = $this->db->query($sql);
        return $result->rows;
    }



    public function getByModificationIdWhereNoChildren($modification_id) {
        $sql = "SELECT node1.* FROM " . DB_PREFIX . "fe_td_node node1
            LEFT JOIN " . DB_PREFIX . "fe_td_node node_child ON node_child.parent_id = node1.id
            WHERE
            node1.modification_id = '" . (int)$modification_id . "'
            AND node_child.id IS NULL";
        $result = $this->db->query($sql);
        return $result->rows;
    }



    public function hasChildrenById($id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "fe_td_node node1
            LEFT JOIN " . DB_PREFIX . "fe_td_node node_child ON node_child.parent_id = node1.id
            WHERE
            node1.id = '" . (int)$id . "'
            AND node_child.id IS NULL";
        $result = $this->db->query($sql);
        return $result->rows ? true : false;
    }

}

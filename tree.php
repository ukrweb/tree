<?php 

class Tree
{
    /**
     * Get data from file
     * 
     * @param string $srcFileName
     * @return array
     */
    public function getData(string $srcFileName) : array
    {
        $rows = [];

        if (file_exists($srcFileName)) {
            $lines = file($srcFileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

            foreach ($lines AS $line) {
                $row = explode("|", $line);
                $rows[] = [
                    'id'       => $row[0],
                    'parentId' => $row[1],
                    'name'     => $row[2],
                ];
            }
        } else {
            echo 'Source file doesn\'t exist';
        }

        return $rows;
    }

    /**
     * Build tree structure from data
     * 
     * @param array $elements
     * @param int $parentId
     * @param int $level
     * @param string $tree
     * @return string
     */
    public function buildTree(array $elements, int $parentId = 0, int $level = 0, string $tree = '') : string
    {
        foreach ($elements as $element) {
            if ($element['parentId'] == $parentId) {
                $row = str_repeat("-", $level) . $element['name'] . PHP_EOL;
                $tree .= $this->buildTree($elements, $element['id'], $level + 1, $row);
            }
        }

        return $tree;
    }

    /**
     * Save tree to file
     * 
     * @param string $destFileName
     * @param string $tree
     */
    public function saveTree(string $destFileName, string $tree)
    {
        file_put_contents($destFileName, print_r(rtrim($tree, PHP_EOL), true));
    }
}

$tree = new Tree();
$rows = $tree->getData('data.txt');
if ($rows) {
    $tree->saveTree('tree.txt', $tree->buildTree($rows));
}
    
?>

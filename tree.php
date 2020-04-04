<?php
class Tree
{
    private $branches = [];
    private $parents = [];
    private $lastDepth = 0;

    public function parse($lines)
    {
        foreach ($lines as $key => $value) {
            $key = $key + 1;
            for ($depth = 0; $depth < strlen($value); $depth++) {
                if ($value[$depth] == '-') {
                    $char = $value[$depth + 2];
                    break;
                }
            }
            if ($depth > 0) {
                if ($depth != $this->lastDepth) {
                    if (!isset($this->parents[$depth]) || $depth > $this->lastDepth) {
                        $this->parents[$depth] = $key - 1;
                    }
                }
            }
            $newdata = [
                'value' => $char,
                'id' => $key,
                'indent' => $depth,
                'parent' => $this->parents[$depth] ?? 0
            ];
            $this->lastDepth = $depth;
            array_push($this->branches, $newdata);
        }
        return $this->create($this->branches);
    }

    public function create($elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as &$element) {
            if (isset($element['parent']) && $element['parent'] == $parentId) {
                $children = $this->create($elements, $element['id']);
                $element['children'] = $children;
                if (isset($element['parent'], $element['id'])) {
                    unset($element['parent'], $element['id']);
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}

$lines = [
    '- A',
    ' - B',
    '  - C',
    '  - D',
    ' - E',
    '  - F',
    '   - G',
    ' - H'
];

echo '<pre>';
$tree = new Tree();
print_r($tree->parse($lines));
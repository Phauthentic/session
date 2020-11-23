
protected function assignArrayByPath(array &$array, string $path, $value): void
{
    $keys = explode('.', $path);

    foreach ($keys as $key) {
        $array = &$array[$key];
    }

    $array = $value;
}

protected function readArrayByPath(array $a, $path, $default = null)
{
    $current = $a;
    $p = strtok($path, '.');

    while ($p !== false) {
        if (!isset($current[$p])) {
            return $default;
        }
        $current = $current[$p];
        $p = strtok('.');
    }

    return $current;
}

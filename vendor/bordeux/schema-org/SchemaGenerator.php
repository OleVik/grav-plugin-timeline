<?php

/**
 * Class SchemaGenerator
 *
 * <code>
 * $generator = new SchemaGenerator();
 * $generator->generate();
 * </code>
 *
 * @author Krzysztof Bednarczyk
 *
 */
class SchemaGenerator
{

    /**
     * @var string
     */
    protected $schemaNamespace = "\\Bordeux\\SchemaOrg\\";

    /**
     * @var string
     */
    protected $schemaJsonUrl = 'http://schema.rdfs.org/all.json';


    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $delimiter = "\\";


    /**
     * @var string default DIRECTORY_SEPERATOR
     */
    protected $fileDelimiter;

    /**
     * @var string[]
     */
    protected $reservedWords = array(
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
    );

    /**
     * Generator constructor.
     * @author Krzysztof Bednarczyk
     */
    public function __construct()
    {
        $this->path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src';
        $this->fileDelimiter = DIRECTORY_SEPARATOR;
    }

    /**
     * Get path value
     * @author Krzysztof Bednarczyk
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path value
     * @author Krzysztof Bednarczyk
     * @param string $path
     * @return  $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * Get schemaNamespace value
     * @author Krzysztof Bednarczyk
     * @return string
     */
    public function getSchemaNamespace()
    {
        return $this->schemaNamespace;
    }

    /**
     * Set schemaNamespace value
     * @author Krzysztof Bednarczyk
     * @param string $schemaNamespace
     * @return  $this
     */
    public function setSchemaNamespace($schemaNamespace)
    {
        $this->schemaNamespace = $schemaNamespace;
        return $this;
    }

    /**
     * Get delimiter value
     * @author Krzysztof Bednarczyk
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Set delimiter value
     * @author Krzysztof Bednarczyk
     * @param string $delimiter
     * @return  $this
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Get fileDelimiter value
     * @author Krzysztof Bednarczyk
     * @return string
     */
    public function getFileDelimiter()
    {
        return $this->fileDelimiter;
    }

    /**
     * Set fileDelimiter value
     * @author Krzysztof Bednarczyk
     * @param string $fileDelimiter
     * @return  $this
     */
    public function setFileDelimiter($fileDelimiter)
    {
        $this->fileDelimiter = $fileDelimiter;
        return $this;
    }



    /**
     * Get schemaJsonUrl value
     * @author Krzysztof Bednarczyk
     * @return string
     */
    public function getSchemaJsonUrl()
    {
        return $this->schemaJsonUrl;
    }

    /**
     * Set schemaJsonUrl value
     * @author Krzysztof Bednarczyk
     * @param string $schemaJsonUrl
     * @return  $this
     */
    public function setSchemaJsonUrl($schemaJsonUrl)
    {
        $this->schemaJsonUrl = $schemaJsonUrl;
        return $this;
    }

    /**
     * Get reservedWords value
     * @author Krzysztof Bednarczyk
     * @return array
     */
    public function getReservedWords()
    {
        return $this->reservedWords;
    }

    /**
     * Set reservedWords value
     * @author Krzysztof Bednarczyk
     * @param array $reservedWords
     * @return  $this
     */
    public function setReservedWords($reservedWords)
    {
        $this->reservedWords = $reservedWords;
        return $this;
    }


    /**
     * @author Krzysztof Bednarczyk
     * @return mixed
     */
    public function getSchemaData()
    {
        return json_decode(file_get_contents($this->getSchemaJsonUrl()));
    }


    /**
     * @author Krzysztof Bednarczyk
     * @param string $name
     * @return string
     */
    protected function getClassName($name)
    {
        if (empty($name)) {
            return $name;
        }

        if (!in_array(strtolower($name), $this->reservedWords)) {
            return $name;
        }

        return $name . "_";
    }


    /**
     * @author Krzysztof Bednarczyk
     */
    public function generate()
    {

        $schema = $this->getSchemaData();


        foreach ($schema->types as $key => $type) {

            $classRawName = $this->getClassName($key);

            $namespace = [];
            foreach ($type->ancestors as $extends) {
                $namespace[] = $this->getClassName($extends);
            }


            $className = $namespace;
            $className[] = $classRawName;

            $extendsClass = empty($namespace) ? ['Element'] : $namespace;
            $source = $this->generateClass($schema, $className, $extendsClass, $type);


            $namespaceDirectory = $this->path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $namespace);
            $filePath = $this->path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $className) . ".php";

            if (!file_exists($namespaceDirectory)) {
                mkdir($namespaceDirectory, 0777, true);
            }

            file_put_contents($filePath, $source);
        }


        return true;
    }


    /**
     * @author Krzysztof Bednarczyk
     * @param object $schema
     * @param array $className
     * @param array $extends
     * @param object $type
     * @return string
     */
    protected function generateClass($schema, array $className, array $extends, $type)
    {


        $namespace = $className;
        $classNameString = array_pop($namespace);

        $namespaceString =
            ltrim(empty($namespace) ?
            rtrim($this->getSchemaNamespace(), $this->delimiter) :
            $this->getSchemaNamespace() . implode($this->delimiter, $namespace), $this->delimiter);



        $extendsString = $this->getSchemaNamespace() . implode($this->delimiter, $extends);


        $properties = [];
        foreach ($type->properties as $prop) {
            $el = $this->generateProperty($schema, $classNameString, $prop, $type->ancestors);
            if ($el) {
                $properties[] = $el;
            }

        }

        $propertiesString = empty($properties) ? " * " : implode("\n", $properties);


        return "<?php

namespace {$namespaceString};

/**
 * Generated by bordeux/schema
 *
 * @author Krzysztof Bednarczyk <schema@bordeux.net>
 * @link http://schema.org/{$classNameString}
 *
 *
{$propertiesString}
 */
 class {$classNameString} extends {$extendsString} {

 }";


    }


    /**
     * @author Krzysztof Bednarczyk
     * @param $schema
     * @param string $className
     * @param string $propName
     * @param $ancestors
     * @return null|string
     */
    protected function generateProperty($schema, $className, $propName, $ancestors)
    {
        foreach ($ancestors as $class) {
            if (in_array($propName, $schema->types->{$class}->properties)) {
                return null;
            }
        }

        $types = [];
        foreach ($schema->properties->{$propName}->ranges as $range) {

            if ($range == 'Text' || $range == 'URL') {
                $range = 'string';
            }

            if (in_array($range, array("DateTime", "Date"))) {
                $range = $this->getSchemaNamespace() . "SchemaDateTime";
            }

            if (isset($schema->types->{$range})) {
                $tmp = [];
                foreach ($schema->types->{$range}->ancestors as $parent) {
                    $tmp[] = $this->getClassName($parent);
                }
                $range = $this->getSchemaNamespace() . implode($this->delimiter, $tmp). $this->delimiter . $range;
            }

            $types[] = $range;
            $types[] = $range . "[]"; //support arrays
        }

        $comment = $schema->properties->{$propName}->comment_plain;
        $methodName = ucfirst($propName);


        $getters = [];
        foreach ($types as $type) {
            $getters[] = "set{$methodName}({$type} \${$propName} )";
        }

        $dataTypeString = implode("|", $types);


        return
            " * -------------------------------- {$methodName} ---------------------------------------------
 *
 * @property {$dataTypeString} {$propName}
 *
 * @method {$dataTypeString} get{$methodName}() {$comment}
 *
 * @method {$className} " . implode(" ", $getters) . "{$comment}
 *
 *";
    }

}
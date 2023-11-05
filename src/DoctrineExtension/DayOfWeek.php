<?php

namespace App\DoctrineExtension;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * @see https://stackoverflow.com/questions/53418286/match-against-doctrine-extension-configuration
 * @see https://stackoverflow.com/questions/12771882/doctrine-2-1-dayofweek-function-alternative
 */
class DayOfWeek extends FunctionNode
{
    public $date;

    /**
     * @override
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'DAYOFWEEK('.$sqlWalker->walkArithmeticPrimary($this->date).')';
    }

    /**
     * @override
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}

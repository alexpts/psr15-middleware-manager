<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ServerRequestInterface;

class PathResolver
{
    /**
     * @param string $path
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isMatch(string $path, ServerRequestInterface $request): bool
    {
        $regexp = $this->makeRegExp($path);
        return preg_match('~^'.$regexp.'$~Uiu', $request->getUri()->getPath(), $values) === 1;
    }

    public function makeRegExp(string $path): string
    {
        $regexp = $path;
        $placeholders = [];

        if (preg_match_all('~{(.*)}~Uu', $regexp, $placeholders)) {
            foreach ($placeholders[0] as $index => $match) {
                $name = $placeholders[1][$index];
                $replace = '[^\/]+';
                $replace = '(?<'.$name.'>' . $replace . ')';
                $regexp = str_replace($match, $replace, $regexp);
            }
        }

        return $regexp;
    }
}

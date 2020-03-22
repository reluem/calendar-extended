<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Tests\Twig;

use Contao\BackendCustom;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\CoreBundle\Tests\TestCase;
use Contao\CoreBundle\Twig\Extension\ContaoTemplateExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests the ContaoTemplateExtension class.
 *
 * @author Jim Schmid <https://github.com/sheeep>
 */
class ContaoTemplateExtensionTest extends TestCase
{
    /**
     * Tests the renderContaoBackendTemplate() method.
     */
    public function testRendersTheContaoBackendTemplate()
    {
        $backendRoute = $this
            ->getMockBuilder(BackendCustom::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTemplateObject', 'run'])
            ->getMock()
        ;

        $template = new \stdClass();

        $backendRoute
            ->expects($this->once())
            ->method('getTemplateObject')
            ->willReturn($template)
        ;

        $backendRoute
            ->expects($this->once())
            ->method('run')
            ->willReturn(new Response())
        ;

        $framework = $this->mockContaoFramework(null, null, [], [
            BackendCustom::class => $backendRoute,
        ]);

        $extension = $this->getExtension($framework);

        $extension->renderContaoBackendTemplate([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
        ]);

        $this->assertSame('a', $template->a);
        $this->assertSame('b', $template->b);
        $this->assertSame('c', $template->c);
    }

    /**
     * Tests the getFunctions() method.
     */
    public function testAddsTheRenderContaoBackEndTemplateFunction()
    {
        $extension = $this->getExtension();
        $functions = $extension->getFunctions();

        $renderBaseTemplateFunction = array_filter($functions, function (\Twig_SimpleFunction $function) {
            return 'render_contao_backend_template' === $function->getName();
        });

        $this->assertCount(1, $renderBaseTemplateFunction);
    }

    /**
     * Tests the scope restriction.
     */
    public function testDoesNotRenderTheBackEndTemplateIfNotInBackEndScope()
    {
        $this->assertEmpty($this->getExtension(null, 'frontend')->renderContaoBackendTemplate());
    }

    /**
     * Returns a template extension object.
     *
     * @param ContaoFrameworkInterface|null $framework
     * @param string                        $scope
     *
     * @return ContaoTemplateExtension
     */
    private function getExtension(ContaoFrameworkInterface $framework = null, $scope = 'backend')
    {
        $request = new Request();
        $request->attributes->set('_scope', $scope);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        if (null === $framework) {
            $framework = $this->mockContaoFramework();
        }

        return new ContaoTemplateExtension($requestStack, $framework, $this->mockScopeMatcher());
    }
}

<?php
namespace Oro\BugBundle\Tests;

use Symfony\Component\DomCrawler\Crawler;

trait TestHelper
{
    public function checkValidation(Crawler $crawler, $ids)
    {
        $errorsBlocks = [];
        $countError = 0;

        $crawler->filter('.validation-error')->each(
            function (Crawler $node) use (&$errorsBlocks, &$countError) {
                $errorsBlocks[] = $node->parents()->html();
                $countError++;
            }
        );
        foreach ($errorsBlocks as $block) {
            $this->assertTrue($this->checkErrorList($block, $ids));
        }
        $this->assertCount($countError, $ids);
    }

    /**
     * @param $block
     * @param array $ids
     * @return bool
     */
    private function checkErrorList($block, array $ids)
    {
        foreach ($ids as $id) {
            if (strpos($block, $id)) {
                return true;
            }
        }

        return false;
    }
}

<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 17.11.14, 07:45 
 */
namespace Fabfuel\Prophiler\Toolbar\Formatter;

class LogFormatter extends BenchmarkFormatter
{
    /**
     * @return string
     */
    public function getLabel()
    {
        $metadata = $this->getMetadata();
        $severity = $metadata['severity'];
        return sprintf(
            '<span class="label %s">%s</span>',
            $this->getColorClass(),
            strtoupper($severity)
        );
    }

    /**
     * @return string
     */
    public function getColorClass()
    {
        $metadata = $this->getMetadata();
        $severity = $metadata['severity'];
        return sprintf(
            'severity-%s',
            $severity
        );
    }
}

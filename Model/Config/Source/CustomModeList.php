<?php
 
namespace Unienvios\Cotacao\Model\Config\Source;
 
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
 
class CustomModeList extends AbstractSource
{
 
    public function getOptionArray()
    {
        $options = [];
        $options[] = (__('CMS Page HIDE'));
        $options[] = (__('CMS Page SHOW'));
        return $options;
    }
 
    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }
 
    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }
 
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}

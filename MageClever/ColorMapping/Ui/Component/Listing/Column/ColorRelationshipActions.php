<?php
declare(strict_types=1);

namespace MageClever\ColorMapping\Ui\Component\Listing\Column;

class ColorRelationshipActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_PATH_DELETE = 'mageclever_colormapping/colorrelationship/delete';
    const URL_PATH_EDIT = 'mageclever_colormapping/colorrelationship/edit';
    protected $urlBuilder;
    const URL_PATH_DETAILS = 'mageclever_colormapping/colorrelationship/details';

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $title = $item['parent_color_option_text'];
                if (isset($item['colorrelationship_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'colorrelationship_id' => $item['colorrelationship_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'colorrelationship_id' => $item['colorrelationship_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete mapping of parent color "%1"', $title),
                                'message' => __('Are you sure you want to delete mapping of "%1" parent color?', $title),
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}


<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\Grid\View;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactory;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Resource\Metadata\MetadataInterface;

/**
 * @mixin ResourceGridViewFactory
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ResourceGridViewFactorySpec extends ObjectBehavior
{
    function let(DataProviderInterface $dataProvider)
    {
        $this->beConstructedWith($dataProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactory');
    }

    function it_implements_resource_grid_view_factory_interface()
    {
        $this->shouldImplement(ResourceGridViewFactoryInterface::class);
    }

    function it_uses_data_provider_to_create_a_view_with_data_and_definition(
        DataProviderInterface $dataProvider,
        Grid $grid,
        Parameters $parameters,
        MetadataInterface $resourceMetadata,
        RequestConfiguration $requestConfiguration
    ) {
        $expectedResourceGridView = new ResourceGridView(
            ['foo', 'bar'],
            $grid->getWrappedObject(),
            $parameters->getWrappedObject(),
            $resourceMetadata->getWrappedObject(),
            $requestConfiguration->getWrappedObject()
        );

        $dataProvider->getData($grid, $parameters)->willReturn(['foo', 'bar']);

        $this->create($grid, $parameters, $resourceMetadata, $requestConfiguration)->shouldBeSameResourceGridViewAs($expectedResourceGridView);
    }

    public function getMatchers()
    {
        return [
            'beSameResourceGridViewAs' => function ($subject, $key) {
                if (!$subject instanceof ResourceGridView || !$key instanceof ResourceGridView) {
                    return false;
                }

                return serialize($subject) === serialize($key);
            },
        ];
    }
}


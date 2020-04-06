<?php

namespace spec\DansMaCulotte\PayPal\Ipn;

use PhpSpec\ObjectBehavior;

class MessageSpec extends ObjectBehavior
{
    public function let(): void
    {
        $data = [
            'foo' => 'bar',
            'baz' => 'quz'
        ];

        $this->beConstructedWith($data);
    }

    public function it_should_retrieve_a_property(): void
    {
        $this->get('foo')->shouldReturn('bar');
        $this->get('baz')->shouldReturn('quz');
    }

    public function it_should_retrieve_all_properties(): void
    {
        $this->getAll()->shouldReturn([
            'foo' => 'bar',
            'baz' => 'quz'
        ]);
    }

    public function it_should_return_an_empty_string_when_retrieving_a_non_existent_property(): void
    {
        $this->get('bar')->shouldReturn('');
    }

    public function it_can_be_represented_as_a_string(): void
    {
        $this->__toString()->shouldReturn('foo=bar&baz=quz');
    }

    public function it_should_url_encode_property_values_when_represented_as_a_string(): void
    {
        $data = [
            'foo' => 'foo + bar (baz)'
        ];

        $this->beConstructedWith($data);

        $this->__toString()->shouldReturn('foo=foo+%2B+bar+%28baz%29');
    }

    public function it_should_accept_a_string_of_raw_post_data_for_its_data_source(): void
    {
        $data = 'foo=bar&baz=quz';

        $this->beConstructedWith($data);

        $this->get('foo')->shouldReturn('bar');
        $this->get('baz')->shouldReturn('quz');
    }

    public function it_should_url_decode_values_when_using_a_string_of_raw_post_data_for_its_data_source(): void
    {
        $data = 'foo=foo+%2B+bar+%28baz%29';

        $this->beConstructedWith($data);

        $this->get('foo')->shouldReturn('foo + bar (baz)');
    }
}

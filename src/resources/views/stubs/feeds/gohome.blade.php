@php
    use App\Utils\ImagesCrawler;
    use App\Utils\Formatters\Gohome;
@endphp

@extends('stubs.feeds.layouts.yrl')

@section('offer')
    @foreach ($crmObjects as $crmObject)
        @php
            try {
                $path = $crmObject->category == 'flat'
                    ? 'kvartiry-komnaty/flats'
                    : ($crmObject->category == 'house' ? 'doma-dachi-uchastki' : 'kommercheskaya-nedvizhimost');
        @endphp

        <offer internal-id="{{ $crmObject->id }}">
            <type>{{ $type = Gohome::type($crmObject) }}</type>

            @if ($type == 'загородная продажа')
                <house-type>{{ Gohome::houseType($crmObject) }}</house-type>
            @elseif ($type == 'коммерческая продажа')
                <commerce-type>{{ Gohome::commerceType($crmObject) }}</commerce-type>
            @endif

            <creation-date>{{ $crmObject->created_at }}</creation-date>
            <last-update-date>{{ $crmObject->updated_at }}</last-update-date>

            <location>
                @if ($crmObject->jandexGeolocation['province'])
                    <region>{{ $crmObject->jandexGeolocation['province'] }}</region>
                @endif

                @if ($crmObject->jandexGeolocation['locality'])
                    <locality-name>{{ $locality = $crmObject->jandexGeolocation['locality'] }}</locality-name>
                @endif

                @if ($type == 'загородная продажа' && $crmObject->jandexGeolocation['area'])
                    <state>{{ str_replace(' район', '', $crmObject->jandexGeolocation['area']) }}</state>
                @endif

                @if ($crmObject->jandexGeolocation['street'])
                    <address>{{ $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '') }}</address>
                @endif

                @if (isset($locality) && $locality == 'Минск' && $crmObject->jandexGeolocation['district'])
                    <area-name>{{ $crmObject->jandexGeolocation['district'] }}</area-name>
                @endif

                <latitude>{{ $crmObject->jandexGeolocation['lat'] }}</latitude>
                <longitude>{{ $crmObject->jandexGeolocation['long'] }}</longitude>
            </location>

            <sales-agent>
                <name>ООО "Бугриэлт"</name>

                @if (isset($crmObject->manager->phones[0]))
                    <phone>+{{ $crmObject->manager->phones[0] }}</phone>
                @endif

                @if (isset($crmObject->manager->phones[1]))
                    <phone_dop>+{{ $crmObject->manager->phones[1] }}</phone_dop>
                @endif

                @if ($crmObject->manager->email)
                    <email>{{ $crmObject->manager->email }}</email>
                @endif
            </sales-agent>

            @if ($crmObject->price)
                <price>
                    <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                </price>
            @endif

            @foreach (ImagesCrawler::crawleImages("https://bugrealt.by/{$path}/{$crmObject->lot}") as $image)
                <image>{{ $image }}</image>
            @endforeach

            @if ($crmObject->description)
                <description>{{ $crmObject->description }}</description>
            @endif

            @if ($crmObject->area)
                <area>
                    <value>{{ $crmObject->area }}</value>
                </area>
            @endif

            @if ($crmObject->kitchen_area)
                <kitchen-space>
                    <value>{{ $crmObject->kitchen_area }}</value>
                </kitchen-space>
            @endif

            @if ($crmObject->living_space)
                <living-space>
                    <value>{{ $crmObject->living_space }}</value>
                </living-space>
            @endif

            @if ($type == 'загородная продажа' && $crmObject->land_area)
                <house-space>
                    <value>{{ $crmObject->land_area * 100 }}</value>
                </house-space>
            @endif

            @if ($crmObject->rooms)
                <rooms>{{ $crmObject->rooms }}</rooms>
                <rooms-offered>{{ $crmObject->rooms }}</rooms-offered>
            @endif

            @if ($crmObject->floor_apartment)
                <floor>{{ $crmObject->floor_apartment }}</floor>
            @endif

            @if ($crmObject->number_of_floors)
                <floors-total>{{ $crmObject->number_of_floors }}</floors-total>
            @endif

            @if ($crmObject->year_built)
                <built-year>{{ $crmObject->year_built }}</built-year>
            @endif

            @if ($type == 'продажа')
                <bathroom-unit>{{ Gohome::bathroomUnit($crmObject) }}</bathroom-unit>
            @endif
        </offer>

        @php
            } catch (Throwable $e) {}

            $feedGenerateStatus->increment('generated');
            $feedGenerateStatus->save();
        @endphp
    @endforeach
@endsection

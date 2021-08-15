<?php

function cleanUpVersion(string $version): string
{
    [$major, $minor] = explode('.', $version);

    return $major . '.' . $minor;
}

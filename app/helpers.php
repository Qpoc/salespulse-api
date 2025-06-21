<?php

if (!function_exists('paginate_if_needed')) {
    function paginate_if_needed($query, $perPage = null)
    {
        $perPage = (int) ($perPage ?? request('per_page') ?? env('DEFAULT_PER_PAGE', 15));

        if (request()->has('page')) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}

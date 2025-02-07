<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait FilterableTrait
{
    /**
     * Get a list of records with optional filters and pagination.
     *
     * @param array $parameters
     * @return array
     */
    public function getListWithFilter(array $parameters = []): array
    {
        $searchText = $parameters['requestData']['search_text'] ?? null;
        $gender = $parameters['requestData']['gender'] ?? null;
        $paginationCount = $parameters['paginate'] ?? null;
        $path = $parameters['path'] ?? '';
        $selectFields = $parameters['select'] ?? ['*'];

        $query = $this->select($selectFields);

        if (!empty($searchText)) {
            $cleanSearchText = str_replace(' ', '', $searchText);
            $query->where(function ($subQuery) use ($cleanSearchText) {
                $subQuery->orWhere(DB::raw('REPLACE(name, " ", "")'), 'like', "%$cleanSearchText%")
                    ->orWhere(DB::raw('REPLACE(email, " ", "")'), 'like', "%$cleanSearchText%");
            });
        }

        if (!empty($gender)) {
            $query->where('gender', $gender);
        }

        if (empty($paginationCount)) {
            return $query->orderBy('id', 'desc')->get()->toArray();
        }

        $paginatedResults = $query->sortable()->paginate($paginationCount)->setPath($path);
        $result = $query->orderBy('id', 'desc')->get();

        return [
            'result' => $result,
            'page_count' => $paginatedResults->lastPage(),
            'paginate' => $paginatedResults,
        ];
    }
}

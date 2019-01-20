<?php

namespace Baas\LaravelVisitorLogger\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Baas\LaravelVisitorLogger\App\Http\Traits\IpAddressDetails;
use Baas\LaravelVisitorLogger\App\Http\Traits\UserAgentDetails;
use Baas\LaravelVisitorLogger\App\Models\VisitorActivity;

class LaravelVisitorLoggerController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, IpAddressDetails, UserAgentDetails, ValidatesRequests;

    private $_rolesEnabled;
    private $_rolesMiddlware;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->_rolesEnabled = config('LaravelVisitorLogger.rolesEnabled');
        $this->_rolesMiddlware = config('LaravelVisitorLogger.rolesMiddlware');

        if ($this->_rolesEnabled) {
            $this->middleware($this->_rolesMiddlware);
        }
    }

    /**
     * Add additional details to a collections.
     *
     * @param collection $collectionItems
     *
     * @return collection
     */
    private function mapAdditionalDetails($collectionItems)
    {
        $collectionItems->map(function ($collectionItem) {
            $eventTime = Carbon::parse($collectionItem->updated_at);
            $collectionItem['timePassed'] = $eventTime->diffForHumans();
            $collectionItem['userAgentDetails'] = UserAgentDetails::details($collectionItem->useragent);
            $collectionItem['langDetails'] = UserAgentDetails::localeLang($collectionItem->locale);
            $collectionItem['userDetails'] = config('LaravelVisitorLogger.defaultUserModel')::find($collectionItem->userId);

            return $collectionItem;
        });

        return $collectionItems;
    }

    /**
     * Show the activities log dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAccessLog()
    {
        if (config('LaravelVisitorLogger.loggerPaginationEnabled')) {
            $activities = VisitorActivity::orderBy('created_at', 'desc')->paginate(config('LaravelVisitorLogger.loggerPaginationPerPage'));
            $totalActivities = $activities->total();
        } else {
            $activities = VisitorActivity::orderBy('created_at', 'desc')->get();
            $totalActivities = $activities->count();
        }

        self::mapAdditionalDetails($activities);

        $data = [
            'activities'        => $activities,
            'totalActivities'   => $totalActivities,
        ];

        return View('LaravelVisitorLogger::logger.activity-log', $data);
    }

    /**
     * Show an individual activity log entry.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function showAccessLogEntry(Request $request, $id)
    {
        $visitorActivity = VisitorActivity::findOrFail($id);

        $userDetails = config('LaravelVisitorLogger.defaultUserModel')::find($visitorActivity->userId);
        $userAgentDetails = UserAgentDetails::details($visitorActivity->useragent);
        $ipAddressDetails = IpAddressDetails::checkIP($visitorActivity->ipAddress);
        $langDetails = UserAgentDetails::localeLang($visitorActivity->locale);
        $eventTime = Carbon::parse($visitorActivity->created_at);
        $timePassed = $eventTime->diffForHumans();

        if (config('LaravelVisitorLogger.loggerPaginationEnabled')) {
            $userActivities = VisitorActivity::where('userId', $visitorActivity->userId)
                           ->orderBy('created_at', 'desc')
                           ->paginate(config('LaravelVisitorLogger.loggerPaginationPerPage'));
            $totalUserActivities = $userActivities->total();
        } else {
            $userActivities = VisitorActivity::where('userId', $visitorActivity->userId)
                           ->orderBy('created_at', 'desc')
                           ->get();
            $totalUserActivities = $userActivities->count();
        }

        self::mapAdditionalDetails($userActivities);

        $data = [
            'activity'              => $visitorActivity,
            'userDetails'           => $userDetails,
            'ipAddressDetails'      => $ipAddressDetails,
            'timePassed'            => $timePassed,
            'userAgentDetails'      => $userAgentDetails,
            'langDetails'           => $langDetails,
            'userActivities'        => $userActivities,
            'totalUserActivities'   => $totalUserActivities,
            'isClearedEntry'        => false,
        ];

        return View('LaravelVisitorLogger::logger.activity-log-item', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function clearActivityLog(Request $request)
    {
        $activities = VisitorActivity::all();
        foreach ($activities as $visitorActivity) {
            $visitorActivity->delete();
        }

        return redirect('activity')->with('success', trans('LaravelVisitorLogger::laravel-logger.messages.logClearedSuccessfuly'));
    }

    /**
     * Show the cleared activity log - softdeleted records.
     *
     * @return \Illuminate\Http\Response
     */
    public function showClearedActivityLog()
    {
        if (config('LaravelVisitorLogger.loggerPaginationEnabled')) {
            $activities = VisitorActivity::onlyTrashed()
                ->orderBy('created_at', 'desc')
                ->paginate(config('LaravelVisitorLogger.loggerPaginationPerPage'));
            $totalActivities = $activities->total();
        } else {
            $activities = VisitorActivity::onlyTrashed()
                ->orderBy('created_at', 'desc')
                ->get();
            $totalActivities = $activities->count();
        }

        self::mapAdditionalDetails($activities);

        $data = [
            'activities'        => $activities,
            'totalActivities'   => $totalActivities,
        ];

        return View('LaravelVisitorLogger::logger.activity-log-cleared', $data);
    }

    /**
     * Show an individual cleared (soft deleted) activity log entry.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function showClearedAccessLogEntry(Request $request, $id)
    {
        $visitorActivity = self::getClearedActvity($id);

        $userDetails = config('LaravelVisitorLogger.defaultUserModel')::find($visitorActivity->userId);
        $userAgentDetails = UserAgentDetails::details($visitorActivity->useragent);
        $ipAddressDetails = IpAddressDetails::checkIP($visitorActivity->ipAddress);
        $langDetails = UserAgentDetails::localeLang($visitorActivity->locale);
        $eventTime = Carbon::parse($visitorActivity->created_at);
        $timePassed = $eventTime->diffForHumans();

        $data = [
            'activity'              => $visitorActivity,
            'userDetails'           => $userDetails,
            'ipAddressDetails'      => $ipAddressDetails,
            'timePassed'            => $timePassed,
            'userAgentDetails'      => $userAgentDetails,
            'langDetails'           => $langDetails,
            'isClearedEntry'        => true,
        ];

        return View('LaravelVisitorLogger::logger.activity-log-item', $data);
    }

    /**
     * Get Cleared (Soft Deleted) VisitorActivity - Helper Method.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    private static function getClearedActvity($id)
    {
        $visitorActivity = VisitorActivity::onlyTrashed()->where('id', $id)->get();
        if (count($visitorActivity) != 1) {
            return abort(404);
        }

        return $visitorActivity[0];
    }

    /**
     * Destroy the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyActivityLog(Request $request)
    {
        $activities = VisitorActivity::onlyTrashed()->get();
        foreach ($activities as $visitorActivity) {
            $visitorActivity->forceDelete();
        }

        return redirect('activity')->with('success', trans('LaravelVisitorLogger::laravel-logger.messages.logDestroyedSuccessfuly'));
    }

    /**
     * Restore the specified resource from soft deleted storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreClearedActivityLog(Request $request)
    {
        $activities = VisitorActivity::onlyTrashed()->get();
        foreach ($activities as $visitorActivity) {
            $visitorActivity->restore();
        }

        return redirect('activity')->with('success', trans('LaravelVisitorLogger::laravel-logger.messages.logRestoredSuccessfuly'));
    }
}

<?php

namespace Baas\LaravelVisitorLogger\App\Models;

use \duxet\Rethinkdb\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorActivity extends Model
{
 //   use SoftDeletes;
    
    public $incrementing = false;
//    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'description',
        'userType',
        'userId',
        'route',
        'ipAddress',
        'userAgent',
        'locale',
        'referer',
        'methodType',
    ];

    protected $casts = [
        'description'   => 'string',
        'user'          => 'integer',
        'route'         => 'url',
        'ipAddress'     => 'ipAddress',
        'userAgent'     => 'string',
        'locale'        => 'string',
        'referer'       => 'url',
        'methodType'    => 'string',
    ];

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('LaravelVisitorLogger.loggerDatabaseTable');
        $this->connection = config('LaravelVisitorLogger.loggerDatabaseConnection');
        

        
    }

    /**
     * Get the database connection.
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * Get the database connection.
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * An activity has a user.
     *
     * @var array
     */
    public function user()
    {
        return $this->hasOne(config('LaravelVisitorLogger.defaultUserModel'));
    }

    /**
     * Get a validator for an incoming Request.
     *
     * @param array $merge (rules to optionally merge)
     *
     * @return array
     */
    public static function rules($merge = [])
    {
        return array_merge([
            'description'   => 'required|string',
            'userType'      => 'required|string',
            'userId'        => 'nullable|string',
            'route'         => 'nullable|url',
            'ipAddress'     => 'nullable|ip',
            'userAgent'     => 'nullable|string',
            'locale'        => 'nullable|string',
            'referer'       => 'nullable|url',
            'methodType'    => 'nullable|string',
        ],
        $merge);
    }
}

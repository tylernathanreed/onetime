<?php

namespace App\Foundation\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;
use Illuminate\Cache\Console\ClearCommand as CacheClearCommand;
use Illuminate\Cache\Console\ForgetCommand as CacheForgetCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand;
use Illuminate\Foundation\Console\ConfigCacheCommand;
use Illuminate\Foundation\Console\ConfigClearCommand;
use Illuminate\Foundation\Console\DownCommand;
use Illuminate\Foundation\Console\EnvironmentCommand;
use Illuminate\Foundation\Console\KeyGenerateCommand;
use Illuminate\Foundation\Console\OptimizeClearCommand;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand;
use Illuminate\Foundation\Console\RouteCacheCommand;
use Illuminate\Foundation\Console\RouteClearCommand;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Foundation\Console\StorageLinkCommand;
use Illuminate\Foundation\Console\UpCommand;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Foundation\Console\ViewCacheCommand;
use Illuminate\Foundation\Console\ViewClearCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        // 'About' => AboutCommand::class,
        'CacheClear' => CacheClearCommand::class,
        'CacheForget' => CacheForgetCommand::class,
        'ClearCompiled' => ClearCompiledCommand::class,
        // 'ClearResets' => ClearResetsCommand::class,
        'ConfigCache' => ConfigCacheCommand::class,
        'ConfigClear' => ConfigClearCommand::class,
        // 'Db' => DbCommand::class,
        // 'DbMonitor' => DatabaseMonitorCommand::class,
        // 'DbPrune' => PruneCommand::class,
        // 'DbShow' => ShowCommand::class,
        // 'DbTable' => DatabaseTableCommand::class,
        // 'DbWipe' => WipeCommand::class,
        'Down' => DownCommand::class,
        'Environment' => EnvironmentCommand::class,
        // 'EnvironmentDecrypt' => EnvironmentDecryptCommand::class,
        // 'EnvironmentEncrypt' => EnvironmentEncryptCommand::class,
        // 'EventCache' => EventCacheCommand::class,
        // 'EventClear' => EventClearCommand::class,
        // 'EventList' => EventListCommand::class,
        'KeyGenerate' => KeyGenerateCommand::class,
        'Optimize' => OptimizeCommand::class,
        'OptimizeClear' => OptimizeClearCommand::class,
        'PackageDiscover' => PackageDiscoverCommand::class,
        // 'PruneStaleTagsCommand' => PruneStaleTagsCommand::class,
        // 'QueueClear' => QueueClearCommand::class,
        // 'QueueFailed' => ListFailedQueueCommand::class,
        // 'QueueFlush' => FlushFailedQueueCommand::class,
        // 'QueueForget' => ForgetFailedQueueCommand::class,
        // 'QueueListen' => QueueListenCommand::class,
        // 'QueueMonitor' => QueueMonitorCommand::class,
        // 'QueuePruneBatches' => QueuePruneBatchesCommand::class,
        // 'QueuePruneFailedJobs' => QueuePruneFailedJobsCommand::class,
        // 'QueueRestart' => QueueRestartCommand::class,
        // 'QueueRetry' => QueueRetryCommand::class,
        // 'QueueRetryBatch' => QueueRetryBatchCommand::class,
        // 'QueueWork' => QueueWorkCommand::class,
        'RouteCache' => RouteCacheCommand::class,
        'RouteClear' => RouteClearCommand::class,
        'RouteList' => RouteListCommand::class,
        // 'SchemaDump' => DumpCommand::class,
        // 'Seed' => SeedCommand::class,
        // 'ScheduleFinish' => ScheduleFinishCommand::class,
        // 'ScheduleList' => ScheduleListCommand::class,
        // 'ScheduleRun' => ScheduleRunCommand::class,
        // 'ScheduleClearCache' => ScheduleClearCacheCommand::class,
        // 'ScheduleTest' => ScheduleTestCommand::class,
        // 'ScheduleWork' => ScheduleWorkCommand::class,
        // 'ShowModel' => ShowModelCommand::class,
        'StorageLink' => StorageLinkCommand::class,
        'Up' => UpCommand::class,
        'ViewCache' => ViewCacheCommand::class,
        'ViewClear' => ViewClearCommand::class
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        // 'CacheTable' => CacheTableCommand::class,
        // 'CastMake' => CastMakeCommand::class,
        // 'ChannelList' => ChannelListCommand::class,
        // 'ChannelMake' => ChannelMakeCommand::class,
        // 'ComponentMake' => ComponentMakeCommand::class,
        // 'ConsoleMake' => ConsoleMakeCommand::class,
        // 'ControllerMake' => ControllerMakeCommand::class,
        // 'Docs' => DocsCommand::class,
        // 'EventGenerate' => EventGenerateCommand::class,
        // 'EventMake' => EventMakeCommand::class,
        // 'ExceptionMake' => ExceptionMakeCommand::class,
        // 'FactoryMake' => FactoryMakeCommand::class,
        // 'JobMake' => JobMakeCommand::class,
        // 'LangPublish' => LangPublishCommand::class,
        // 'ListenerMake' => ListenerMakeCommand::class,
        // 'MailMake' => MailMakeCommand::class,
        // 'MiddlewareMake' => MiddlewareMakeCommand::class,
        // 'ModelMake' => ModelMakeCommand::class,
        // 'NotificationMake' => NotificationMakeCommand::class,
        // 'NotificationTable' => NotificationTableCommand::class,
        // 'ObserverMake' => ObserverMakeCommand::class,
        // 'PolicyMake' => PolicyMakeCommand::class,
        // 'ProviderMake' => ProviderMakeCommand::class,
        // 'QueueFailedTable' => FailedTableCommand::class,
        // 'QueueTable' => TableCommand::class,
        // 'QueueBatchesTable' => BatchesTableCommand::class,
        // 'RequestMake' => RequestMakeCommand::class,
        // 'ResourceMake' => ResourceMakeCommand::class,
        // 'RuleMake' => RuleMakeCommand::class,
        // 'ScopeMake' => ScopeMakeCommand::class,
        // 'SeederMake' => SeederMakeCommand::class,
        // 'SessionTable' => SessionTableCommand::class,
        'Serve' => ServeCommand::class,
        // 'StubPublish' => StubPublishCommand::class,
        // 'TestMake' => TestMakeCommand::class,
        'VendorPublish' => VendorPublishCommand::class
    ];
}

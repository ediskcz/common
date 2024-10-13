<?php

namespace Edisk\Common;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use DoctrineExtensions\Query\Mysql\MatchAgainst;
use RuntimeException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\DoctrineDbalStore;

class Services
{
    private static array $services = [];

    public static function entityManager()
    {
        if (isset(self::$services['entity_manager'])) {
            return self::$services['entity_manager'];
        }

        if (!defined('MAIN_DOCROOT')) {
            define('MAIN_DOCROOT', __DIR__ . '/../../');
        }

        $paths = [
            MAIN_DOCROOT . '/src/Entity',
        ];
        $isDevMode = $_ENV['APP_ENV'] === 'DEVELOPMENT';

        try {
            $dsnParser = new DsnParser(['mysql' => 'mysqli', 'postgres' => 'pdo_pgsql']);
            $connectionParams = $dsnParser->parse($_ENV['DATABASE_URL'] ?? '');

            if ($isDevMode) {
                $queryCache = new ArrayAdapter();
                $metadataCache = new ArrayAdapter();
            } else {
                $queryCache = new PhpFilesAdapter('doctrine_queries');
                $metadataCache = new PhpFilesAdapter('doctrine_metadata');
            }

            $config = new Configuration;
            $config->setMetadataCache($metadataCache);
            $driverImpl = new AttributeDriver($paths, true);
            $config->setMetadataDriverImpl($driverImpl);
            $config->setQueryCache($queryCache);
            $config->setProxyDir(MAIN_DOCROOT . '/var/cache/doctrine/orm/Proxies');
            $config->setProxyNamespace('EntityManager\Proxies');
            $config->setAutoGenerateProxyClasses($isDevMode);
            $config->addCustomStringFunction('MATCH', MatchAgainst::class);

            $connection = DriverManager::getConnection($connectionParams, $config);
            $entityManager = new EntityManager($connection, $config);
        } catch (Exception|MissingMappingDriverImplementation $e) {
            throw new RuntimeException('Cannot create entity manager', 0, $e);
        }

        self::$services['entity_manager'] = $entityManager;

        return self::$services['entity_manager'];
    }

    public static function lockFactory()
    {
        if (isset(self::$services['lock_factory'])) {
            return self::$services['lock_factory'];
        }

        $databaseDsn = $_ENV['DATABASE_URL'] ?? '';
        $store = new DoctrineDbalStore(
            $databaseDsn,
            [
                'db_table' => 'lock_keys',
            ]
        );

        self::$services['lock_factory'] = new LockFactory($store);

        return self::$services['lock_factory'];
    }
}

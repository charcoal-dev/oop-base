<?php
/*
 * This file is a part of "charcoal-dev/oop-base" package.
 * https://github.com/charcoal-dev/oop-base
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/charcoal-dev/oop-base/blob/master/LICENSE
 */

declare(strict_types=1);

class UsersRegistry extends \Charcoal\OOP\DependencyInjection\AbstractObjectsRegistry
{
    public function __construct(
        public readonly DumbDatabase $db,
        public readonly DumbCache    $cache,
    )
    {
        parent::__construct(DumbUser::class);
    }

    public function findId(int $userId, bool $useCache = true): DumbUser
    {
        return $this->getOrResolve("users_id:" . $userId, ["id", $userId], [$useCache]);
    }

    public function findUsername(string $username, bool $useCache = true): DumbUser
    {
        return $this->getOrResolve("users_username:" . $username, ["username", $username], [$useCache]);
    }

    // returns object or NULL
    private function resolveFromCache(string $key): ?DumbUser
    {
        return $this->cache->get($key);
    }

    // throws an Exception
    private function resolveFromDb(string $col, int|string $value): DumbUser
    {
        return $this->db->checkInTable("users", $col, $value);
    }

    protected function resolve(string $key, array $args, array $opts): DumbUser
    {
        if ($opts[0]) { // Use Cache?
            $cachedUser = $this->resolveFromCache($key);
        }

        if (isset($cachedUser)) {
            // Any further checks in retrieved model from cache?
            $cachedUser->testTag = "cache";
            return $cachedUser;
        }

        // This method should throw exception if no user model retrieved
        $resolved = $this->resolveFromDb($args[0], $args[1]);
        $resolved->testTag = "db";
        return $resolved;
    }

    public function getBindingKeys(object $object): array
    {
        /** @var \DumbUser $object */
        return [
            "users_id:" . $object->id,
            "users_username:" . $object->username
        ];
    }

    /**
     * @param object $object
     * @param array $keys
     * @param array $opts
     * @return void
     */
    protected function onStoreCallback(object $object, array $keys, array $opts): void
    {
        if (!$opts[0]) { // Use Cache?
            return;
        }

        $primaryKey = array_shift($keys);
        $this->cache->set($primaryKey, $object);

        foreach ($keys as $refKey) {
            $this->cache->createReference($refKey, $primaryKey);
        }
    }

    public function unset(DumbUser $user): void
    {
        $this->unsetObject($user);
    }
}


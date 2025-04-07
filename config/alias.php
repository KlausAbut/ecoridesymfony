<?php
// config/alias.php
if (!class_exists(\MongoDB\Driver\Manager::class)) {
    class_alias(Alcaeus\MongoDbAdapter\Manager::class, \MongoDB\Driver\Manager::class);
}
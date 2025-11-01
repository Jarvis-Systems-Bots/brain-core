<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines the centralized (master) vector memory architecture shared across all agents.
Ensures consistent storage, synchronization, conflict resolution, and governance for embeddings at scale.
PURPOSE
)]
class VectorMasterStorageStrategy extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('topology-master')
            ->text('Central master node with exclusive write access.')
            ->example('central-vector-db')->key('location')
            ->example('exclusive')->key('write')
            ->example('all')->key('read');

        $this->guideline('topology-replica')
            ->text('Read-only replica nodes with caching enabled.')
            ->example('n>=1')->key('count')
            ->example('disabled')->key('write')
            ->example('allowed')->key('read')
            ->example('enabled')->key('cache');

        $this->guideline('schema-vectors')
            ->example('uuid (pk)')
            ->example('content (text)')
            ->example('embedding (vector)')
            ->example('timestamp (utc)')
            ->example('source (agent_id|system)')
            ->example('version (int)')
            ->example('index: embedding_dim')
            ->example('index: timestamp');

        $this->guideline('access-control-policy')
            ->example('Only Brain orchestrator may perform master writes and schema migrations.')
            ->example('Agents submit write-intents via queue; Brain batches and commits.')
            ->example('Replicas are read-only for agents; local MCP2 caches permitted.');

        $this->guideline('access-control-validation')
            ->example('All write-intents must include agent_id, checksum, and dedupe-key.')
            ->example('Requests without valid auth/signature are rejected.');

        $this->guideline('sync-policy')
            ->text('Master-replica asynchronous synchronization with periodic consistency.')
            ->example('master-replica (asynchronous with periodic consistency)')->key('mode')
            ->example('every 5m')->key('frequency')
            ->example('eventual-consistency ≤ 10m')->key('window')
            ->example('batch-size ≤ 500 records')->key('batch')
            ->example('exponential-backoff x2 up to 5 attempts')->key('retry');

        $this->guideline('conflict-resolution')
            ->example('If uuid matches and version differs → keep higher version.')
            ->example('If timestamp difference ≤ 2s and content differs → prefer brain-approved entry.')
            ->example('If duplicate by dedupe-key → merge metadata, keep newest timestamp.');

        $this->guideline('ingestion-pipeline')
            ->example('Agent creates write-intent (uuid, content, embedding, checksum, dedupe-key).')
            ->example('Brain validates intent (schema, size, policy) and enqueues batch.')
            ->example('Master commits batch; replicas receive diff via stream.');

        $this->guideline('ingestion-limits')
            ->example('max-embedding-size = 1536')
            ->example('max-record-size-bytes = 128KB')
            ->example('max-qps = 100');

        $this->guideline('retrieval-policy')
            ->example('Agents query nearest replica first; fallback to master if miss.')
            ->example('Top-N default = 10; similarity ≥ 0.78');

        $this->guideline('retrieval-cache')
            ->example('LRU')->key('strategy')
            ->example('30m')->key('ttl')
            ->example('256MB')->key('max-size');

        $this->guideline('metrics-retrieval')
            ->example('hit-rate ≥ 0.8')
            ->example('latency-p95-ms ≤ 30');

        $this->guideline('maintenance-integrity-check')
            ->example('Run checksum over last N commits; compare across master/replicas.')
            ->example('PRAGMA integrity_check on SQLite replicas weekly.');

        $this->guideline('maintenance-prune')
            ->example('ttl = 45d')
            ->example('strategy: timestamp ASC prune; preserve high-usage vectors');

        $this->guideline('maintenance-vacuum')
            ->example('schedule = weekly')
            ->example('policy: replicas vacuum after prune; master vacuum during low-traffic window');

        $this->guideline('fallback-master-down')
            ->text('Switch all writes to durable queue; replicas serve read-only. Trigger alert and begin master-restore workflow.');

        $this->guideline('fallback-replica-stale')
            ->text('Bypass to master for reads; refresh replica via full sync.');

        $this->guideline('fallback-checksum-mismatch')
            ->text('Quarantine inconsistent records; request re-ingestion from queue.');

        $this->guideline('metrics-observability')
            ->example('sync-success ≥ 0.99')
            ->example('replica-lag-s ≤ 600')
            ->example('conflict-rate ≤ 0.01');

        $this->guideline('alerts')
            ->example('Alert if replica-lag-s > 600 for 3 consecutive checks.')
            ->example('Alert on conflict-rate spike > 0.05 within 10m window.');

        $this->guideline('meta-controls-vector-storage')
            ->text('Strict, token-efficient structure; no prose; CI-parseable.')
            ->example('All schema and policy changes require Architect approval.')->key('governance')
            ->example('All master writes and conflict resolutions are auditable with UTC timestamp and agent_id.')->key('logging');
    }
}

<template lang="html">
  <div class="document-card" :class="getDocumentStatusClass(document.status)">
    <div class="document-header">
      <div class="document-type">
        <i class="fas fa-file-pdf"></i>
        <span>{{ document.type }}</span>
      </div>
      <div class="document-status">
        <span class="status-badge" :class="getStatusBadgeClass(document.status)">
          {{ getStatusText(document.status) }}
        </span>
      </div>
    </div>

    <div class="document-body">
      <h5 class="document-title">{{ document.title }}</h5>
      <p class="document-description">{{ document.description }}</p>

      <div class="document-meta">
        <div class="meta-item">
          <i class="fas fa-calendar"></i>
          <span>{{ formatDate(document.created_at) }}</span>
        </div>
        <div class="meta-item">
          <i class="fas fa-user"></i>
          <span>{{ document.contractor }}</span>
        </div>
        <div class="meta-item" v-if="document.counterpart">
          <i class="fas fa-users"></i>
          <span>{{ document.counterpart }}</span>
        </div>
      </div>
    </div>

    <div class="document-actions">
      <button
        class="btn btn-outline-primary btn-sm"
        @click="$emit('view', document)"
      >
        <i class="fas fa-eye"></i>
        {{ $t("documents.view") }}
      </button>

      <button
        v-if="document.status === 'pending'"
        class="btn btn-success btn-sm"
        @click="$emit('sign', document)"
      >
        <i class="fas fa-signature"></i>
        {{ $t("documents.sign") }}
      </button>

      <a
        v-if="document.download_url"
        :href="document.download_url"
        class="btn btn-outline-secondary btn-sm"
        target="_blank"
      >
        <i class="fas fa-download"></i>
        {{ $t("documents.download") }}
      </a>

      <button
        v-if="document.status === 'pending' && document.can_cancel"
        class="btn btn-outline-danger btn-sm"
        @click="$emit('cancel', document)"
      >
        <i class="fas fa-times"></i>
        {{ $t("documents.cancel") }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DocumentCard',
  props: {
    document: {
      type: Object,
      required: true
    }
  },
  methods: {
    getDocumentStatusClass(status) {
      return {
        'status-signed': status === 'signed',
        'status-pending': status === 'pending',
        'status-cancelled': status === 'cancelled'
      };
    },
    getStatusBadgeClass(status) {
      return {
        'badge-success': status === 'signed',
        'badge-warning': status === 'pending',
        'badge-danger': status === 'cancelled'
      };
    },
    getStatusText(status) {
      const statusMap = {
        'signed': this.$t('documents.signed'),
        'pending': this.$t('documents.pending'),
        'cancelled': this.$t('documents.cancelled')
      };
      return statusMap[status] || status;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
  }
}
</script>

<style scoped>
.document-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: all 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.document-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.document-card.status-signed {
  border-left: 4px solid #28a745;
}

.document-card.status-pending {
  border-left: 4px solid #ffc107;
}

.document-card.status-cancelled {
  border-left: 4px solid #dc3545;
}

.document-header {
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.document-type {
  display: flex;
  align-items: center;
  color: #6c757d;
  font-size: 0.9rem;
}

.document-type i {
  margin-right: 0.5rem;
  color: #dc3545;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.badge-success {
  background-color: #d4edda;
  color: #155724;
}

.badge-warning {
  background-color: #fff3cd;
  color: #856404;
}

.badge-danger {
  background-color: #f8d7da;
  color: #721c24;
}

.document-body {
  padding: 1rem;
  flex-grow: 1;
}

.document-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.document-description {
  color: #6c757d;
  font-size: 0.9rem;
  margin-bottom: 1rem;
  line-height: 1.4;
}

.document-meta {
  margin-bottom: 1rem;
}

.meta-item {
  display: flex;
  align-items: center;
  margin-bottom: 0.5rem;
  font-size: 0.85rem;
  color: #6c757d;
}

.meta-item i {
  width: 16px;
  margin-right: 0.5rem;
  color: #adb5bd;
}

.document-actions {
  padding: 1rem;
  border-top: 1px solid #e9ecef;
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.document-actions .btn {
  font-size: 0.8rem;
  padding: 0.375rem 0.75rem;
  border-radius: 5px;
  transition: all 0.2s ease;
}

.document-actions .btn:hover {
  transform: translateY(-1px);
}

@media (max-width: 768px) {
  .document-actions {
    flex-direction: column;
  }

  .document-actions .btn {
    width: 100%;
  }
}
</style>

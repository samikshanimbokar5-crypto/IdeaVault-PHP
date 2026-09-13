#!/bin/bash
set -e

# Disable conflicting Apache MPM modules
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2dismod mpm_prefork 2>/dev/null || true

# Enable only prefork for PHP stability
a2enmod mpm_prefork

# Hand control over to Apache in the foreground
exec apache2-foreground
FROM debian:stretch

ADD install.sh install.sh
RUN chmod +x install.sh && sh ./install.sh && rm install.sh

#VOLUME ["/var/lib/varnish", "/etc/varnish"]
EXPOSE 80

ENV CACHE_SIZE      256M
ENV VARNISHD_PARAMS -p http_resp_size=98304 -p http_resp_hdr_len=65536 -p workspace_client=256k -p workspace_backend=256k

ADD start.sh /start.sh
RUN chmod +x /start.sh

CMD "/start.sh"
FROM alpine:latest AS builder
COPY test /abc/test

FROM alpine:3.6
COPY --from=builder /abc/test /bcd/test

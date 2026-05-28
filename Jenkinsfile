pipeline {
    agent any

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        disableConcurrentBuilds()
        timestamps()
    }

    environment {
        APP_REPOSITORY = 'sihiy1/sasimga-jember'
        NGINX_REPOSITORY = 'sihiy1/sasimga-nginx'
        STACK_NAME = 'sasimga-jember'
        DOCKERHUB_CREDENTIALS = 'dockerhub-credentials'
        DOCKER_BUILDKIT = '1'
    }

    stages {
        stage('Validate') {
            steps {
                sh '''
                    set -e
                    docker --version
                    docker compose version
                    docker compose config >/dev/null
                '''
            }
        }

        stage('Build') {
            steps {
                sh '''
                    set -e
                    docker build \
                        --target app \
                        --tag ${APP_REPOSITORY}:${BUILD_NUMBER} \
                        --tag ${APP_REPOSITORY}:latest \
                        -f dockerfile .

                    docker build \
                        --build-context sasimga-app-assets=docker-image://${APP_REPOSITORY}:${BUILD_NUMBER} \
                        --tag ${NGINX_REPOSITORY}:${BUILD_NUMBER} \
                        --tag ${NGINX_REPOSITORY}:latest \
                        -f Dockerfile.nginx .
                '''
            }
        }

        stage('Smoke Test') {
            steps {
                sh '''
                    set -e
                    docker run --rm ${APP_REPOSITORY}:${BUILD_NUMBER} php artisan --version
                '''
            }
        }

        stage('Push') {
            steps {
                withCredentials([usernamePassword(credentialsId: "${DOCKERHUB_CREDENTIALS}", usernameVariable: 'DOCKERHUB_USER', passwordVariable: 'DOCKERHUB_PASS')]) {
                    sh '''
                        set -e
                        echo "$DOCKERHUB_PASS" | docker login -u "$DOCKERHUB_USER" --password-stdin
                        docker push ${APP_REPOSITORY}:${BUILD_NUMBER}
                        docker push ${APP_REPOSITORY}:latest
                        docker push ${NGINX_REPOSITORY}:${BUILD_NUMBER}
                        docker push ${NGINX_REPOSITORY}:latest
                        docker logout
                    '''
                }
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                    set -e
                    export APP_IMAGE_REF=${APP_REPOSITORY}:${BUILD_NUMBER}
                    export NGINX_IMAGE_REF=${NGINX_REPOSITORY}:${BUILD_NUMBER}
                    docker stack deploy --with-registry-auth -c docker-stack.yml ${STACK_NAME}
                    docker stack services ${STACK_NAME}
                '''
            }
        }
    }

    post {
        always {
            sh '''
                docker image prune -f --filter "until=168h" || true
                docker builder prune -f --filter "until=168h" || true
            '''
            cleanWs()
        }
    }
}

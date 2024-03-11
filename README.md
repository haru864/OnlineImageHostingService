# Online Image Hosting Service

ユーザーアカウントを必要とせずに画像をアップロード、共有、表示できるオンライン画像ホスティングサービスです。<br>
このサービスは、ユーザーが自分の画像をアップロードするためのシンプルなウェブインターフェースを提供します。<br>
アップロード後、画像はサーバ上に保存され、画像のための一意なURLが生成されます。ユーザーはこのリンクを他の人と共有し、<br>リンクにアクセスするだけで画像を表示できます。

# Features

発行されるURLを使って、画像の閲覧と削除の2つの操作ができます。<br>
ビューカウンターを通して、アップロードされた画像がウェブ上で何回表示されたかを表示します。<br>
一定期間アクセスされていない画像ファイルはサーバーから削除されます。<br>
また、IPアドレス単位で一定時間内にアップロード可能なファイル数とバイト数をチェックします。<br>
これにより、サーバーの負荷を抑えながらシステムを稼働させられます。<br>
画像ファイルはアプリサーバーで保持することで、システムの応答性を担保しています。

# Usage

ブラウザからサービスにアクセスします。
![image](https://github.com/haru864/OnlineImageHostingService/assets/45516420/e64322ba-85cf-49f7-ba4e-ae18b7b8a00b)

JPEG,PNG,GIFファイルのいずれかを選択してアプロードします。<br>
ファイル形式がこれら以外だったり、ファイルサイズが大きすぎるとエラーになります。<br>
他にもエラー原因があるので、エラー画面に表示されたメッセージを参考に修正してください。

アップロードに成功すると、閲覧用と削除用の2つのURLが発行されます。
![image](https://github.com/haru864/OnlineImageHostingService/assets/45516420/771e2e49-5743-4c54-b171-22d2926551cf)

閲覧用URLにアクセスすると、アップロードした画像が表示されます。<br>
画面上部のビューカウンターで閲覧数を確認できます。
![image](https://github.com/haru864/OnlineImageHostingService/assets/45516420/e7e00e3d-3d44-4497-aa88-22cd4808b63d)

削除用URLにアクセスすると、アップロードした画像を削除します。<br>
削除した画像のデータは戻せないので注意してください。
![image](https://github.com/haru864/OnlineImageHostingService/assets/45516420/c789dbef-cce6-4f05-a85e-9ae104cfe268)
![image](https://github.com/haru864/OnlineImageHostingService/assets/45516420/48d0e6b1-9665-4c3d-aeff-aadd93674365)


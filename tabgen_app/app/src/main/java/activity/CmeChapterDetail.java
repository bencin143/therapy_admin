package activity;

import android.content.pm.ActivityInfo;
import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.media.MediaPlayer;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.CardView;
import android.support.v7.widget.Toolbar;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.KeyEvent;
import android.view.MenuItem;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.MediaController;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.VideoView;

import com.github.rtoshiro.view.video.FullscreenVideoLayout;
import com.nganthoi.salai.tabgen.R;
import com.squareup.picasso.Callback;
import com.squareup.picasso.Picasso;

import java.io.IOException;

import Utils.ConstantValues;
import uk.co.senab.photoview.PhotoView;
import uk.co.senab.photoview.PhotoViewAttacher;

/**
 * Created by atul on 28/7/16.
 */
public class CmeChapterDetail extends AppCompatActivity implements View.OnClickListener{

    String file_type,attachment_url,TOKEN;
    RelativeLayout rlViewer;
    ProgressBar progressBar;
    WebView webView;
    FullscreenVideoLayout vwVideoView;
    PhotoView pvPhoto;
    CardView cvCard;
    Toolbar toolbar;
    LinearLayout llLinear,llVideo;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        file_type=getIntent().getStringExtra("FILE_TYPE");
        attachment_url=getIntent().getStringExtra("ATTATCHMENT_URL");
        TOKEN=getIntent().getStringExtra("TOKEN");
        setContentView(R.layout.activity_chapter_detail);
        Window window = getWindow();
        window.clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
        window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
        int currentapiVersion = android.os.Build.VERSION.SDK_INT;
        if (currentapiVersion >= Build.VERSION_CODES.LOLLIPOP)
        {
            window.setStatusBarColor(getResources().getColor(R.color.colorPrimaryDark));
        }else
        {
        }
        toolbar = (Toolbar) findViewById(R.id.toolbarConversation);
        setSupportActionBar(toolbar);
        getSupportActionBar().setHomeAsUpIndicator(R.drawable.back);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        initComponent();
    }

    private void initComponent() {
        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        progressBar.setMax(100);
        llLinear=(LinearLayout)findViewById(R.id.llLinear);
        rlViewer=(RelativeLayout)findViewById(R.id.rlViewer);
        llVideo=(LinearLayout)findViewById(R.id.llVideo);
        webView = (WebView) findViewById(R.id.webView);
        pvPhoto= (PhotoView) findViewById(R.id.pvPhoto);
        vwVideoView=(FullscreenVideoLayout)findViewById(R.id.vwVideoView);
        if(file_type.contains("image")){
            llVideo.setVisibility(View.GONE);
            llLinear.setVisibility(View.VISIBLE);
            pvPhoto.setVisibility(View.VISIBLE);
            rlViewer.setVisibility(View.GONE);
            vwVideoView.setVisibility(View.GONE);
            final PhotoViewAttacher attacher = new PhotoViewAttacher(pvPhoto);

            Picasso.with(this)
                    .load(""+attachment_url)
                    .into(pvPhoto, new Callback() {
                        @Override
                        public void onSuccess() {
                            attacher.update();
                        }

                        @Override
                        public void onError() {
                        }
                    });
        }else if(file_type.contains("mp4") || file_type.contains("3gp") || file_type.contains("video")){
            setRequestedOrientation (ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
            llVideo.setVisibility(View.VISIBLE);
            llLinear.setVisibility(View.GONE);
            vwVideoView.setVisibility(View.VISIBLE);
            pvPhoto.setVisibility(View.GONE);
            rlViewer.setVisibility(View.GONE);
            Log.v("START","VIDEO"+attachment_url);
            DisplayMetrics metrics = new DisplayMetrics(); getWindowManager().getDefaultDisplay().getMetrics(metrics);
            android.widget.LinearLayout.LayoutParams params = (android.widget.LinearLayout.LayoutParams) vwVideoView.getLayoutParams();
            params.width =  metrics.widthPixels;
            params.height = metrics.heightPixels;
            params.leftMargin = 0;
            vwVideoView.setLayoutParams(params);
            vwVideoView.setOnClickListener(this);
            try {
                // Start the MediaController
                MediaController mediacontroller = new MediaController(
                        CmeChapterDetail.this);
                mediacontroller.setAnchorView(vwVideoView);
                // Get the URL from String VideoURL
                Uri video = Uri.parse(attachment_url);
//                vwVideoView.setMediaController(mediacontroller);
                vwVideoView.setVideoURI(video);

            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }

            vwVideoView.requestFocus();
            vwVideoView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
                // Close the progress bar and play the video
                public void onPrepared(MediaPlayer mp) {
                    vwVideoView.start();
                }
            });

            vwVideoView.start();


        }else if(file_type.contains("mp3") || file_type.contains("audio")){

        }else{
            llVideo.setVisibility(View.GONE);
            llLinear.setVisibility(View.GONE);
            vwVideoView.setVisibility(View.GONE);
            pvPhoto.setVisibility(View.GONE);
            rlViewer.setVisibility(View.VISIBLE);
            webView.setWebViewClient(new WebViewClientDemo());
            webView.setWebChromeClient(new WebChromeClientDemo());
            webView.getSettings().setJavaScriptEnabled(true);
            webView.getSettings().setBuiltInZoomControls(true);
            webView.getSettings().setSupportZoom(true);
            webView.loadUrl("http://docs.google.com/gview?embedded=true&url="
                    + ""+attachment_url);
        }

    }

    @Override
    public void onClick(View v) {
        if (v.getId() == R.id.vcv_img_play) {
            if (vwVideoView.isPlaying()) {
                vwVideoView.pause();
            } else {
                vwVideoView.start();
            }
        } else {
//            if(vwVideoView.isFullscreen()){
//                vwVideoView.setFullscreen(false);
//            }else{
                vwVideoView.setFullscreen(true);
//            }

        }
    }
    private class WebViewClientDemo extends WebViewClient {
        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            view.loadUrl(url);
            return true;
        }
        @Override
        public void onPageFinished(WebView view, String url) {
            super.onPageFinished(view, url);
            progressBar.setVisibility(View.GONE);
            progressBar.setProgress(100);
        }
        @Override
        public void onPageStarted(WebView view, String url, Bitmap favicon) {
            super.onPageStarted(view, url, favicon);
            progressBar.setVisibility(View.VISIBLE);
            progressBar.setProgress(0);
        }
    }
    private class WebChromeClientDemo extends WebChromeClient {
        public void onProgressChanged(WebView view, int progress) {
            progressBar.setProgress(progress);
        }
    }
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if ((keyCode == KeyEvent.KEYCODE_BACK) && webView.canGoBack()) {
            webView.goBack();
            return true;
        }
        else {
            finish();
        }
        return super.onKeyDown(keyCode, event);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()){
            case android.R.id.home:
                onBackPressed();
                break;
        }
        return super.onOptionsItemSelected(item);
    }

}

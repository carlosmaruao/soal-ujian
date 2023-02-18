<div style="color: dimgray">
<strong>Petunjuk :</strong>
  
<p>Dibawah ini terdapat 15 kategori persoalan yang harus diselesaikan. Tugas anda adalah mengurutkan dari no 1 - {{count($major)}}, pada setiap kategorinya mengenai tugas/hobi dari yang PALING anda suka s/d yang PALING anda TIDAK suka.</p>

<p>Jika semua pilihan jawaban TIDAK ADA yang mencerminkan diri anda, silahkan urutkan pernyataan yang paling <strong>memungkinkan/mendekati</strong> hal yang anda sukai.</p>

<p><strong>Tujuan</strong> dari tes minat bakat ini adalah untuk memberikan saran kepada mahasiswa LSPR mengenai jurusan yang dapat dipilih sesuai dengan minat dan bakat yang dimiliki.</p>

<p>Setiap kategori persoalan memiliki {{count($major)}} alternatif jawaban yang menggambarkan {{count($major)}} jurusan yang ada di LSPR, yaitu : <br>
    @foreach ($major as $item)
   <strong>{{$item->initial}}</strong> ({{$item->title}}),
 @endforeach 
</p>
</div> 
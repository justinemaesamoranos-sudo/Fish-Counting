@extends('layouts.app')
@section('title', 'Fish Counts')

@section('content')
<style>
  .group-section {
    margin-bottom: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 88, 170, 0.1);
    overflow: hidden;
  }

  .group-header {
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    border-bottom: 4px solid #004D73;
    user-select: none;
  }

  .group-header:hover {
    background: linear-gradient(135deg, #006B8F 0%, #004D73 100%);
  }

  .group-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .group-toggle {
    font-size: 0.9rem;
    transition: transform 0.3s;
  }

  .group-toggle.collapsed {
    transform: rotate(-90deg);
  }

  .group-content {
    padding: 1.5rem;
    max-height: 1000px;
    overflow: hidden;
    transition: max-height 0.3s ease;
  }

  .group-content.collapsed {
    max-height: 0;
    padding: 0 1.5rem;
  }

  .group-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 6px;
  }

  .group-stat {
    text-align: center;
  }

  .group-stat-label {
    font-size: 0.85rem;
    color: #0088AA;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .group-stat-value {
    font-size: 1.8rem;
    color: #004D73;
    font-weight: 700;
    margin-top: 0.3rem;
  }

  .table-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: auto;
    max-height: 600px;
    border-radius: 6px;
    border: 1px solid #E8F4F8;
  }

  .table-container::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  .table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .table-container::-webkit-scrollbar-thumb {
    background: #0088AA;
    border-radius: 4px;
  }

  .table-container::-webkit-scrollbar-thumb:hover {
    background: #004D73;
  }

  .table-wrapper {
    min-width: min-content;
  }
</style>

  <div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
      <h3 style="color: #004D73;"><i class="fas fa-list" style="color: #0088AA; margin-right: 0.5rem;"></i>All Fish Counts</h3>
      <form method="POST" action="{{ route('fish-counts.delete-all') }}" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete all data? This cannot be undone.');">
        @csrf
        <button type="submit" class="btn btn-danger" style="background-color: #E57373; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 0.4rem; cursor: pointer; font-size: 0.95rem; transition: background 0.3s;" onmouseover="this.style.backgroundColor='#EF5350'" onmouseout="this.style.backgroundColor='#E57373'">
          Delete All Data
        </button>
      </form>
    </div>
    
    @if (session('success'))
      <div class="alert alert-success" style="background-color: #C8E6C9; color: #2E7D32; padding: 1rem; border-radius: 0.4rem; margin-bottom: 1rem; border-left: 4px solid #4CAF50;">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger" style="background-color: #FFCDD2; color: #C62828; padding: 1rem; border-radius: 0.4rem; margin-bottom: 1rem; border-left: 4px solid #F44336;">
        {{ session('error') }}
      </div>
    @endif
    
    @forelse($groups as $group)
      <div class="group-section">
        <div class="group-header" onclick="toggleGroup(this)">
          <h4>
            <i class="fas fa-calendar-alt" style="color: white;"></i>
            {{ $group['date'] }}
            <span style="font-size: 0.85rem; opacity: 0.9;">(Group #{{ $group['group_id'] }})</span>
          </h4>
          <span class="group-toggle"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="group-content">
          <div class="group-stats">
            <div class="group-stat">
              <div class="group-stat-label">Total Images</div>
              <div class="group-stat-value">{{ count($group['images']) }}</div>
            </div>
            <div class="group-stat">
              <div class="group-stat-label">Total Fish</div>
              <div class="group-stat-value">{{ $group['total_count'] }}</div>
            </div>
            <div class="group-stat">
              <div class="group-stat-label">Average</div>
              <div class="group-stat-value">{{ round($group['total_count'] / count($group['images']), 1) }}</div>
            </div>
          </div>

          <div class="table-container">
            <div class="table-wrapper">
              <table class="simple-table">
                <thead>
                  <tr style="background-color: #E8F4F8; color: #004D73;">
                    <th><i class="fas fa-hashtag" style="color: #0088AA; margin-right: 0.3rem;"></i>ID</th>
                    <th><i class="fas fa-image" style="color: #0088AA; margin-right: 0.3rem;"></i>Image</th>
                    <th><i class="fas fa-box" style="color: #0088AA; margin-right: 0.3rem;"></i>Cage</th>
                    <th><i class="fas fa-fish" style="color: #0088AA; margin-right: 0.3rem;"></i>Count</th>
                    <th><i class="fas fa-clock" style="color: #0088AA; margin-right: 0.3rem;"></i>Time</th>
                    <th><i class="fas fa-cog" style="color: #0088AA; margin-right: 0.3rem;"></i>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($group['images'] as $img)
                    <tr>
                      <td class="text-center">{{ $img->id }}</td>
                      <td class="text-center">
                        <img 
                          src="{{ asset($img->file_path) }}" 
                          width="80" 
                          class="clickable-image" 
                          data-index="{{ $loop->index }}"
                          data-id="{{ $img->id }}"
                          data-path="{{ asset($img->file_path) }}"
                          data-name="Image {{ $img->id }}"
                          style="cursor: pointer; border-radius: 4px;"
                        >
                      </td>
                      <td class="text-center">{{ $img->cage }}</td>
                      <td class="text-center">{{ $img->count }}</td>
                      <td class="text-center">{{ $img->created_at->format('H:i:s') }}</td>
                      <td class="text-center">
                        <form method="POST" action="{{ route('fish-counts.delete', $img->id) }}" style="display: inline;" onsubmit="return confirm('Delete this image? This cannot be undone.');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn-delete" title="Delete" style="background: none; border: none; cursor: pointer; padding: 0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas fa-trash-alt" style="color: #E57373; font-size: 1rem;"></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div style="text-align: center; padding: 3rem; color: #0088AA;">
        <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
        <p style="font-size: 1.1rem; margin: 0;">No fish count data available</p>
      </div>
    @endforelse
  </div>

  <!-- Floating Image Modal -->
  <div id="imageModal" class="image-modal" style="display: none;">
    <div class="image-modal-content">
      <span class="image-modal-close">&times;</span>
      
      <div class="image-modal-header">
        <h2 id="imageName">Image</h2>
      </div>

      <div class="image-modal-body">
        <img id="modalImage" src="" alt="Enlarged Image" class="modal-image">
      </div>

      <div class="image-modal-footer">
        <button id="prevBtn" class="nav-button nav-prev">← Previous</button>
        <span id="imageCounter">1 / 1</span>
        <button id="nextBtn" class="nav-button nav-next">Next →</button>
      </div>
    </div>
  </div>

  <script>
    function toggleGroup(header) {
      const content = header.nextElementSibling;
      const toggle = header.querySelector('.group-toggle');
      content.classList.toggle('collapsed');
      toggle.classList.toggle('collapsed');
    }

    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('imageModal');
      const closeBtn = document.querySelector('.image-modal-close');
      const prevBtn = document.getElementById('prevBtn');
      const nextBtn = document.getElementById('nextBtn');
      const images = document.querySelectorAll('.clickable-image');
      let currentIndex = 0;

      const imageArray = Array.from(images);

      images.forEach((img, index) => {
        img.addEventListener('click', function () {
          currentIndex = index;
          openModal();
        });
      });

      closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
      });

      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.style.display = 'none';
        }
      });

      prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + imageArray.length) % imageArray.length;
        openModal();
      });

      nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % imageArray.length;
        openModal();
      });

      document.addEventListener('keydown', (e) => {
        if (modal.style.display !== 'none') {
          if (e.key === 'ArrowLeft') {
            prevBtn.click();
          } else if (e.key === 'ArrowRight') {
            nextBtn.click();
          } else if (e.key === 'Escape') {
            modal.style.display = 'none';
          }
        }
      });

      function openModal() {
        const currentImg = imageArray[currentIndex];
        document.getElementById('modalImage').src = currentImg.dataset.path;
        document.getElementById('imageName').textContent = currentImg.dataset.name;
        document.getElementById('imageCounter').textContent = `${currentIndex + 1} / ${imageArray.length}`;
        
        modal.style.display = 'flex';
      }
    });
  </script>

  <style>
    .image-modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      justify-content: center;
      align-items: center;
    }

    .image-modal-content {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      max-width: 90%;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    .image-modal-close {
      position: absolute;
      right: 20px;
      top: 10px;
      font-size: 32px;
      font-weight: bold;
      color: #0F2D3C;
      cursor: pointer;
      z-index: 1001;
      transition: color 0.3s;
    }

    .image-modal-close:hover {
      color: #6C9BC9;
    }

    .image-modal-header {
      padding: 20px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    .image-modal-header h2 {
      margin: 0;
      color: #0F2D3C;
      font-size: 20px;
    }

    .image-modal-body {
      flex: 1;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: auto;
    }

    .modal-image {
      max-width: 100%;
      max-height: 70vh;
      border-radius: 8px;
    }

    .image-modal-footer {
      padding: 20px;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
    }

    .nav-button {
      background-color: #6C9BC9;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: background-color 0.3s;
    }

    .nav-button:hover {
      background-color: #0F2D3C;
    }

    .nav-button:active {
      transform: scale(0.98);
    }

    #imageCounter {
      font-weight: 600;
      color: #0F2D3C;
      min-width: 80px;
      text-align: center;
    }

    .clickable-image:hover {
      opacity: 0.8;
      transition: opacity 0.3s;
    }

    .btn-delete {
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.5rem;
      transition: transform 0.2s, opacity 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-delete:hover {
      transform: scale(1.2);
      opacity: 0.8;
    }

    .btn-delete:active {
      transform: scale(0.95);
    }
  </style>
@endsection
